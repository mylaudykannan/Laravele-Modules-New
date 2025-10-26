<?php

namespace App\Modules\News\Controller;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\News\Models\Newscategory;
use App\Modules\News\Models\DraftNews;
use App\Modules\News\Models\DraftNewsSection;
use Illuminate\Support\Str;

class DraftNewsController extends Controller
{
    public function view($slug)
    {
        $news = DraftNews::where('slug', $slug)->where('status','0')->first();
        if(empty($news))
            abort(404);
        return view('News::admin.view', ['news' => $news]);
    }

    public function index()
    {
        $newss = DraftNews::orderBy('created_at', 'desc');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $newss = $newss->where(function ($query){
                $query->where('title', 'like', '%' . $_GET['search'] . '%')->orWhere('slug', 'like', '%' . $_GET['search'] . '%');
            });
        }
        if (isset($_GET['lang']) && $_GET['lang'] != '') {
            $newss = $newss->where('locale', $_GET['lang']);
        } else {
            $newss = $newss->where('locale', 'en');
        }
        $newss = $newss->paginate(10);        
        return view('News::admin.draft.index', ['newss' => $newss]);
    }

    public function create()
    {
        $data['newscategory'] = Newscategory::active()->orderBy('title', 'ASC')->get()->toArray();
        $news['pointslug'] = 'new';
        //adding default config to all
        $configar = config('news.section.' . $news['pointslug']);
        foreach(config('news.section.default') as $confdk => $confdv){
            $configar[$confdk] = $confdv;
        }
        config(['news.section.' . $news['pointslug'] => $configar]);
        //end
        $data['news'] = $news;
        $data['slug'] = 'new';
        return view('News::admin.draft.create', $data);
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
        $news = new DraftNews();

        $slug = Str::slug($request->title);
        sc:
        $slugcount = DraftNews::where('slug', $slug)->count();
        if ($slugcount > 0) {
            $slug = $slug . '-1';
            goto sc;
        }

        $news->title = $request->title;
        $news->slug = $slug;
        $news->locale = $locale;
        $news->pointslug = $slug;
        $news->image = $request->image;
        $news->content = $request->content;
        $news->meta_description = $request->meta_description;
        $news->meta_keywords = $request->meta_keywords;
        $news->analytics = $request->analytics;
        $news->status = $request->status;
        $news->publish_on = date('Y-m-d H:i:s', strtotime($request->publish_on));

        $news->save();

        $configar = config('news.section.' . $news['pointslug']);
        foreach(config('news.section.default') as $confdk => $confdv){
            $configar[$confdk] = $confdv;
        }
        config(['news.section.' . $news['pointslug'] => $configar]);
        if (config('news.section.' . $slug)) {
            foreach (config('news.section.' . $slug) as $k => $v) {
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
                    $match = ['type' => $k, 'news_id' => $news->id];
                    $data = ['title' => $k, 'type' => $k, 'content' => $sectioncontent, 'order' => 0, 'news_id' => $news->id, 'slug' => $itemslug];
                    DraftNewsSection::updateOrCreate($match, $data);
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
                        $match = ['type' => $k, 'news_id' => $news->id, 'order' => $i];
                        $data = ['title' => $slug, 'type' => $k, 'content' => $sectioncontent, 'order' => $i, 'news_id' => $news->id, 'slug' => $itemslug];
                        if(config('news.section.' . $slug . '.' .$k. '.parent'))
                        $data['parent'] = $request->input($k . config('news.section.' . $slug . '.' .$k. '.parent') . $i);
                        DraftNewsSection::updateOrCreate($match, $data);
                    }
                }
            }
        }
        if(isset($_GET['fromnews'])){
            $message = array('message.level' => 'alert-success', 'message.content' => 'News Created', 'redirect' => URL('admin/news'));
            return response()->json($message);
        }
        else{
            $message = array('message.level' => 'alert-success', 'message.content' => 'News Created');
            return redirect('admin/draftnews')->with($message);
        }
    }

    public function edit($pointslug)
    {
        $locale = app()->getLocale();
        $news = DraftNews::with('sections')->where('pointslug', $pointslug)->where('locale', $locale)->first();
        $newssections = [];
        //adding default config to all
        $configar = config('news.section.' . $pointslug);
        foreach(config('news.section.default') as $confdk => $confdv){
            if($confdk=='content' && config('news.hide-content.'.$pointslug))
                continue;
            $configar[$confdk] = $confdv;
        }
        config(['news.section.' . $pointslug => $configar]);
        //end
        if (!empty($news) && config('news.section.' . $pointslug)) {
            foreach (config('news.section.' . $pointslug) as $k => $v) {
                $newssections[$k] = DraftNewsSection::where('news_id', $news->id)->where('type', $k)->orderBy('order','ASC')->get()->toArray();
            }
        }
        $newscategory = Newscategory::active()->orderBy('title', 'ASC')->get()->toArray();
        return view('News::admin.draft.edit', ['news' => $news, 'newssections' => $newssections, 'slug' => $pointslug, 'newscategory' => $newscategory]);
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
        $news = DraftNews::where(['slug' =>  $slug, 'locale' => $locale])->first();
        if ($news === null) {
            $news = new DraftNews();
            $news->slug = $slug;
            $news->pointslug = $slug;
        }
        $news->title = $request->title;
        $news->locale = $locale;
        $news->image = $request->image;
        $news->content = $request->content;
        $news->meta_description = $request->meta_description;
        $news->meta_keywords = $request->meta_keywords;
        $news->analytics = $request->analytics;
        $news->status = $request->status;
        $news->publish_on = date('Y-m-d H:i:s', strtotime($request->publish_on));

        $news->save();

        $configar = config('news.section.' . $news['pointslug']);
        foreach(config('news.section.default') as $confdk => $confdv){
            $configar[$confdk] = $confdv;
        }
        config(['news.section.' . $news['pointslug'] => $configar]);
        if (config('news.section.' . $slug)) {
            foreach (config('news.section.' . $slug) as $k => $v) {
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
                    $match = ['type' => $k, 'news_id' => $news->id];
                    $data = ['title' => $k, 'type' => $k, 'content' => $sectioncontent, 'order' => 0, 'news_id' => $news->id, 'slug' => $itemslug];
                    DraftNewsSection::updateOrCreate($match, $data);
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
                        $match = ['type' => $k, 'news_id' => $news->id, 'order' => $i];
                        $data = ['title' => $slug, 'type' => $k, 'content' => $sectioncontent, 'order' => $i, 'news_id' => $news->id, 'slug' => $itemslug];
                        DraftNewsSection::updateOrCreate($match, $data);
                    }
                }
            }
        }

        if(isset($_GET['fromnews'])){
            $message = array('message.level' => 'alert-success', 'message.content' => 'News Updated', 'redirect' => URL('admin/news'));
            return response()->json($message);
        }
        else{
            $message = array('message.level' => 'alert-success', 'message.content' => 'News Updated');
            return redirect()->back()->with($message);
        }
    }

    public function destroy($id)
    {
        $news = DraftNews::find($id);
        $news->delete();
        $news = DraftNewsSection::where('news_id', $id)->delete();
        $message = array('message.level' => 'alert-success', 'message.content' => 'News Deleted');
        return redirect()->back()->with($message);
    }

    public function destroysection($id)
    {
        $news = DraftNewsSection::find($id);
        $news->delete();
        $message = array('message.level' => 'alert-success', 'message.content' => 'News Sectin Deleted');
        return redirect()->back()->with($message);
    }
}
