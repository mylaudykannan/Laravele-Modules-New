<?php

namespace App\Modules\Page\Controller;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Page\Models\Pagecategory;
use App\Modules\Page\Models\DraftPage;
use App\Modules\Page\Models\DraftPageSection;
use Illuminate\Support\Str;

class DraftPageController extends Controller
{
    public function view($slug)
    {
        $page = DraftPage::where('slug', $slug)->where('status','0')->first();
        if(empty($page))
            abort(404);
        return view('Page::admin.view', ['page' => $page]);
    }

    public function index()
    {
        $pages = DraftPage::orderBy('created_at', 'desc');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $pages = $pages->where(function ($query){
                $query->where('title', 'like', '%' . $_GET['search'] . '%')->orWhere('slug', 'like', '%' . $_GET['search'] . '%');
            });
        }
        if (isset($_GET['lang']) && $_GET['lang'] != '') {
            $pages = $pages->where('locale', $_GET['lang']);
        } else {
            $pages = $pages->where('locale', 'en');
        }
        $pages = $pages->paginate(10);        
        return view('Page::admin.draft.index', ['pages' => $pages]);
    }

    public function create()
    {
        $data['pagecategory'] = Pagecategory::active()->orderBy('title', 'ASC')->get()->toArray();
        $page['pointslug'] = 'new';
        //adding default config to all
        $configar = config('page.section.' . $page['pointslug']);
        foreach(config('page.section.default') as $confdk => $confdv){
            $configar[$confdk] = $confdv;
        }
        config(['page.section.' . $page['pointslug'] => $configar]);
        //end
        $data['page'] = $page;
        $data['slug'] = 'new';
        return view('Page::admin.draft.create', $data);
    }

    public function store(Request $request)
    {
        $locale = app()->getLocale();
        
        $validatedData = $request->validate([
            'title' => 'required|max:250',
            'image' => 'required',
            'meta_description' => 'max:6000',
            'meta_keywords' => 'max:6000',
            'analytics' => 'max:6000',
            'status' => 'required'
        ]);
        $page = new DraftPage();

        $slug = Str::slug($request->title);
        sc:
        $slugcount = DraftPage::where('slug', $slug)->count();
        if ($slugcount > 0) {
            $slug = $slug . '-1';
            goto sc;
        }

        $page->title = $request->title;
        $page->slug = $slug;
        $page->locale = $locale;
        $page->pointslug = $slug;
        $page->image = $request->image;
        $page->content = $request->content;
        $page->meta_description = $request->meta_description;
        $page->meta_keywords = $request->meta_keywords;
        $page->analytics = $request->analytics;
        $page->status = $request->status;
        $page->publish_on = date('Y-m-d H:i:s', strtotime($request->publish_on));

        $page->save();

        $configar = config('page.section.' . $page['pointslug']);
        foreach(config('page.section.default') as $confdk => $confdv){
            $configar[$confdk] = $confdv;
        }
        config(['page.section.' . $page['pointslug'] => $configar]);
        if (config('page.section.' . $slug)) {
            foreach (config('page.section.' . $slug) as $k => $v) {
                if (isset($v['static'])) {
                    $content = [];
                    $sectionvalidation = [];
                    $sectionvalidationmessage = [];
                    $itemslug = '';
                    foreach ($v['fields'] as $fk => $fv) {
                        if (isset($fv['validation'])) {
                            $sectionvalidation[$k . $fk] = $fv['validation'];
                        }
                        if (isset($fv['message'])) {
                            foreach ($fv['message'] as $fvmk => $fvmv) {
                                $sectionvalidationmessage[$k . $fk . '.' . $fvmk] = $fvmv;
                            }
                        }
                        $content[$fk] = $request->input($k . $fk);
                        if (isset($v['slug']) && $v['slug']==$fk){
                            $itemslug = Str::slug($request->input($k . $fk));
                        }
                    }
                    $validatedData = $request->validate(
                        $sectionvalidation,
                        $sectionvalidationmessage
                    );

                    $sectioncontent = json_encode($content);
                    $match = ['type' => $k, 'page_id' => $page->id];
                    $data = ['title' => $k, 'type' => $k, 'content' => $sectioncontent, 'order' => 0, 'page_id' => $page->id, 'slug' => $itemslug];
                    DraftPageSection::updateOrCreate($match, $data);
                } else {
                    $sectionvalidation = [];
                    $sectionvalidationmessage = [];
                    for ($i = 1; $i <= $request->input('loopcount' . $k); $i++) {
                        $content = [];
                        $itemslug = '';
                        foreach ($v['fields'] as $fk => $fv) {

                            if (isset($fv['validation'])) {
                                $sectionvalidation[$k . $fk . $i] = $fv['validation'];
                            }
                            if (isset($fv['message'])) {
                                foreach ($fv['message'] as $fvmk => $fvmv) {
                                    $sectionvalidationmessage[$k . $fk . $i . '.' . $fvmk] = $fvmv;
                                }
                            }


                            $content[$fk] = $request->input($k . $fk . $i);
                            if (isset($v['slug']) && $v['slug']==$fk){
                                $itemslug = Str::slug($request->input($k . $fk . $i));
                            }
                        }
                        $validatedData = $request->validate(
                            $sectionvalidation,
                            $sectionvalidationmessage
                        );
                        $sectioncontent = json_encode($content);
                        $match = ['type' => $k, 'page_id' => $page->id, 'order' => $i];
                        $data = ['title' => $slug, 'type' => $k, 'content' => $sectioncontent, 'order' => $i, 'page_id' => $page->id, 'slug' => $itemslug];
                        if(config('page.section.' . $slug . '.' .$k. '.parent'))
                        $data['parent'] = $request->input($k . config('page.section.' . $slug . '.' .$k. '.parent') . $i);
                        DraftPageSection::updateOrCreate($match, $data);
                    }
                }
            }
        }
        if(isset($_GET['frompage'])){
            $message = array('message.level' => 'alert-success', 'message.content' => 'Page Created', 'redirect' => URL('admin/page'));
            return response()->json($message);
        }
        else{
            $message = array('message.level' => 'alert-success', 'message.content' => 'Page Created');
            return redirect('admin/draftpage')->with($message);
        }
    }

    public function edit($pointslug)
    {
        $locale = app()->getLocale();
        $page = DraftPage::with('sections')->where('pointslug', $pointslug)->where('locale', $locale)->first();
        $pagesections = [];
        //adding default config to all
        $configar = config('page.section.' . $pointslug);
        foreach(config('page.section.default') as $confdk => $confdv){
            if($confdk=='content' && config('page.hide-content.'.$pointslug))
                continue;
            $configar[$confdk] = $confdv;
        }
        config(['page.section.' . $pointslug => $configar]);
        //end
        if (!empty($page) && config('page.section.' . $pointslug)) {
            foreach (config('page.section.' . $pointslug) as $k => $v) {
                $pagesections[$k] = DraftPageSection::where('page_id', $page->id)->where('type', $k)->orderBy('order','ASC')->get()->toArray();
            }
        }
        $pagecategory = Pagecategory::active()->orderBy('title', 'ASC')->get()->toArray();
        return view('Page::admin.draft.edit', ['page' => $page, 'pagesections' => $pagesections, 'slug' => $pointslug, 'pagecategory' => $pagecategory]);
    }

    public function update(Request $request)
    {
        $locale = app()->getLocale();
        $validatedData = $request->validate([
            'title' => 'required|max:250',
            'image' => 'required',
            'meta_description' => 'max:6000',
            'meta_keywords' => 'max:6000',
            'analytics' => 'max:6000',
            'status' => 'required'
        ]);

        $slug = $request->input('slug');
        $page = DraftPage::where(['slug' =>  $slug, 'locale' => $locale])->first();
        if ($page === null) {
            $page = new DraftPage();
            $page->slug = $slug;
            $page->pointslug = $slug;
        }
        $page->title = $request->title;
        $page->locale = $locale;
        $page->image = $request->image;
        $page->content = $request->content;
        $page->meta_description = $request->meta_description;
        $page->meta_keywords = $request->meta_keywords;
        $page->analytics = $request->analytics;
        $page->status = $request->status;
        $page->publish_on = date('Y-m-d H:i:s', strtotime($request->publish_on));

        $page->save();

        $configar = config('page.section.' . $page['pointslug']);
        foreach(config('page.section.default') as $confdk => $confdv){
            $configar[$confdk] = $confdv;
        }
        config(['page.section.' . $page['pointslug'] => $configar]);
        if (config('page.section.' . $slug)) {
            foreach (config('page.section.' . $slug) as $k => $v) {
                if (isset($v['static'])) {
                    $content = [];
                    $sectionvalidation = [];
                    $sectionvalidationmessage = [];
                    $itemslug = '';
                    foreach ($v['fields'] as $fk => $fv) {
                        if (isset($fv['validation'])) {
                            $sectionvalidation[$k . $fk] = $fv['validation'];
                        }
                        if (isset($fv['message'])) {
                            foreach ($fv['message'] as $fvmk => $fvmv) {
                                $sectionvalidationmessage[$k . $fk . '.' . $fvmk] = $fvmv;
                            }
                        }
                        $content[$fk] = $request->input($k . $fk);
                        if (isset($v['slug']) && $v['slug']==$fk){
                            $itemslug = Str::slug($request->input($k . $fk));
                        }
                    }
                    $validatedData = $request->validate(
                        $sectionvalidation,
                        $sectionvalidationmessage
                    );

                    $sectioncontent = json_encode($content);
                    $match = ['type' => $k, 'page_id' => $page->id];
                    $data = ['title' => $k, 'type' => $k, 'content' => $sectioncontent, 'order' => 0, 'page_id' => $page->id, 'slug' => $itemslug];
                    DraftPageSection::updateOrCreate($match, $data);
                } else {
                    $sectionvalidation = [];
                    $sectionvalidationmessage = [];
                    for ($i = 1; $i <= $request->input('loopcount' . $k); $i++) {
                        $content = [];
                        $itemslug = '';
                        foreach ($v['fields'] as $fk => $fv) {

                            if (isset($fv['validation'])) {
                                $sectionvalidation[$k . $fk . $i] = $fv['validation'];
                            }
                            if (isset($fv['message'])) {
                                foreach ($fv['message'] as $fvmk => $fvmv) {
                                    $sectionvalidationmessage[$k . $fk . $i . '.' . $fvmk] = $fvmv;
                                }
                            }


                            $content[$fk] = $request->input($k . $fk . $i);
                            if (isset($v['slug']) && $v['slug']==$fk){
                                $itemslug = Str::slug($request->input($k . $fk . $i));
                            }
                        }
                        $validatedData = $request->validate(
                            $sectionvalidation,
                            $sectionvalidationmessage
                        );
                        $sectioncontent = json_encode($content);
                        $match = ['type' => $k, 'page_id' => $page->id, 'order' => $i];
                        $data = ['title' => $slug, 'type' => $k, 'content' => $sectioncontent, 'order' => $i, 'page_id' => $page->id, 'slug' => $itemslug];
                        DraftPageSection::updateOrCreate($match, $data);
                    }
                }
            }
        }

        if(isset($_GET['frompage'])){
            $message = array('message.level' => 'alert-success', 'message.content' => 'Page Updated', 'redirect' => URL('admin/page'));
            return response()->json($message);
        }
        else{
            $message = array('message.level' => 'alert-success', 'message.content' => 'Page Updated');
            return redirect()->back()->with($message);
        }
    }

    public function destroy($id)
    {
        $page = DraftPage::find($id);
        $page->delete();
        $page = DraftPageSection::where('page_id', $id)->delete();
        $message = array('message.level' => 'alert-success', 'message.content' => 'Page Deleted');
        return redirect()->back()->with($message);
    }

    public function destroysection($id)
    {
        $page = DraftPageSection::find($id);
        $page->delete();
        $message = array('message.level' => 'alert-success', 'message.content' => 'Page Sectin Deleted');
        return redirect()->back()->with($message);
    }
}
