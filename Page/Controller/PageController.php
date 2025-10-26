<?php

namespace App\Modules\Page\Controller;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Page\Models\Page;
use App\Modules\Page\Models\PageSection;
use App\Modules\Page\Models\Pagecategory;
use App\Modules\Page\Models\DraftPage;
use Illuminate\Support\Str;
use Auth;
use DB;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with('draft')->orderBy('created_at', 'desc');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $pages = $pages->where(function ($query) {
                $query->where('title', 'like', '%' . $_GET['search'] . '%')->orWhere('slug', 'like', '%' . $_GET['search'] . '%');
            });
        }
        if (isset($_GET['lang']) && $_GET['lang'] != '') {
            $pages = $pages->where('locale', $_GET['lang']);
        } else {
            $pages = $pages->where('locale', 'en');
        }
        $pages = $pages->paginate(10);
        return view('Page::admin.index', ['pages' => $pages]);
    }

    public function create()
    {
        $data['pagecategory'] = Pagecategory::active()->orderBy('title', 'ASC')->get()->toArray();
        $page['pointslug'] = 'new';
        //adding default config to all
        $configar = config('page.section.' . $page['pointslug']);
        foreach (config('page.section.default') as $confdk => $confdv) {
            $configar[$confdk] = $confdv;
        }
        config(['page.section.' . $page['pointslug'] => $configar]);
        //end
        $data['page'] = $page;
        $data['slug'] = 'new';
        return view('Page::admin.create', $data);
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
        $page = new Page();

        $slug = Str::slug($request->title);
        sc:
        $slugcount = Page::where('slug', $slug)->count();
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
        $page->category = (!empty($request->category))?json_encode($request->category):'[]';
        $page->meta_description = $request->meta_description;
        $page->meta_keywords = $request->meta_keywords;
        $page->analytics = $request->analytics;
        $page->status = $request->status;
        $page->publish_on = date('Y-m-d H:i:s', strtotime($request->publish_on));
        $page->created_by = Auth::user()->id;

        $page->save();

        $configar = config('page.section.' . $page['pointslug']);
        foreach (config('page.section.default') as $confdk => $confdv) {
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
                        if (isset($v['slug']) && $v['slug']==$fk) {
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
                    PageSection::updateOrCreate($match, $data);
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
                            if (isset($v['slug']) && $v['slug']==$fk) {
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
                        if (config('page.section.' . $slug . '.' .$k. '.parent')) {
                            $data['parent'] = $request->input($k . config('page.section.' . $slug . '.' .$k. '.parent') . $i);
                        }
                        PageSection::updateOrCreate($match, $data);
                    }
                }
            }
        }

        $message = array('message.level' => 'alert-success', 'message.content' => 'Page Created');
        return redirect('admin/page')->with($message);
    }

    public function show($id)
    {
        return view('Page::admin.show');
    }

    public function edit($pointslug)
    {
        $locale = app()->getLocale();
        $page = Page::with('sections')->where('pointslug', $pointslug)->where('locale', $locale)->first();
        $pagesections = [];
        //adding default config to all
        $configar = config('page.section.' . $pointslug);
        foreach (config('page.section.default') as $confdk => $confdv) {
            if ($confdk=='content' && config('page.hide-content.'.$pointslug)) {
                continue;
            }
            $configar[$confdk] = $confdv;
        }
        config(['page.section.' . $pointslug => $configar]);
        //end
        if (!empty($page) && config('page.section.' . $pointslug)) {
            foreach (config('page.section.' . $pointslug) as $k => $v) {
                $pagesections[$k] = PageSection::with('children')->where('page_id', $page->id)->where('type', $k)->orderBy('order', 'ASC')->get()->toArray();
            }
        }
        $pagecategory = Pagecategory::active()->orderBy('title', 'ASC')->get()->toArray();
        return view('Page::admin.edit', ['page' => $page, 'pagesections' => $pagesections, 'slug' => $pointslug, 'pagecategory' => $pagecategory]);
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
        $page = Page::where(['pointslug' =>  $slug, 'locale' => $locale])->first();
        if ($page === null) {
            $basepage = Page::where(['pointslug' =>  $slug])->first();
            if (!empty($basepage)) {
                $urlslug = $basepage->slug;
            } else {
                $urlslug = $slug;
            }
            $page = new Page();
            $page->slug = $urlslug;
            $page->pointslug = $slug;
        }

        //for slug
        if ($request->slugchange!='') {
            $slugchange = Str::slug($request->slugchange);
            $slugcount = Page::where('pointslug', '!=', $slug)->where(function ($query) use ($slugchange) {
                $query->where('pointslug', '=', $slugchange)->orWhere('slug', '=', $slugchange);
            })->count();
            
            if ($slugcount > 0) {
                return \Redirect::back()->withErrors(['Slug already exists']);
            }
            
            $page->slug = $slugchange;
            $page->save();

            //for draft slug
            $drPage = DraftPage::where(['pointslug' =>  $slug, 'locale' => $locale])->first();
            if (!empty($drPage)) {
                $drPage->slug = $slugchange;
                $drPage->save();
            }
            //for Arabic slug
            $arPage = Page::where(['pointslug' =>  $slug, 'locale' => 'ar'])->first();
            if (!empty($arPage)) {
                $arPage->slug = $slugchange;
                $arPage->save();
            }
            //for Draft Arabic slug
            $ardrPage = DraftPage::where(['pointslug' =>  $slug, 'locale' => 'ar'])->first();
            if (!empty($ardrPage)) {
                $ardrPage->slug = $slugchange;
                $ardrPage->save();
            }
            //for Russian slug
            $ruPage = Page::where(['pointslug' =>  $slug, 'locale' => 'ru'])->first();
            if (!empty($ruPage)) {
                $ruPage->slug = $slugchange;
                $ruPage->save();
            }
            //for Draft Russian slug
            $rudrPage = DraftPage::where(['pointslug' =>  $slug, 'locale' => 'ru'])->first();
            if (!empty($rudrPage)) {
                $rudrPage->slug = $slugchange;
                $rudrPage->save();
            }
            return redirect('admin/page/edit/'.$page->pointslug)->with(['Slug Updated']);
        }

        $page->title = $request->title;
        $page->locale = $locale;
        $page->image = $request->image;
        $page->content = $request->content;
        $page->category = (!empty($request->category))?json_encode($request->category):'[]';
        $page->meta_description = $request->meta_description;
        $page->meta_keywords = $request->meta_keywords;
        $page->analytics = $request->analytics;
        $page->status = $request->status;
        $page->publish_on = date('Y-m-d H:i:s', strtotime($request->publish_on));
        $page->created_by = Auth::user()->id;

        $page->save();

        $configar = config('page.section.' . $page['pointslug']);
        foreach (config('page.section.default') as $confdk => $confdv) {
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
                        if (isset($v['slug']) && $v['slug']==$fk) {
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
                    PageSection::updateOrCreate($match, $data);
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
                            if (isset($v['slug']) && $v['slug']==$fk) {
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
                        if (config('page.section.' . $slug . '.' .$k. '.parent')) {
                            $data['parent'] = $request->input($k . config('page.section.' . $slug . '.' .$k. '.parent') . $i);
                        }
                        PageSection::updateOrCreate($match, $data);
                    }
                }
            }
        }

        if (isset($_GET['fromdraft'])) {
            $message = array('message.level' => 'alert-success', 'message.content' => 'Page Updated', 'redirect' => URL('admin/page'));
            return response()->json($message);
        } else {
            $message = array('message.level' => 'alert-success', 'message.content' => 'Page Updated');
            return redirect()->back()->with($message);
        }
    }

    public function updateSlug(Request $request)
    {
        $validatedData = $request->validate([
            'slugchange' => 'required|max:250',
        ]);
        $slug = $request->input('slug');
        $locale = app()->getLocale();

        $page = Page::where(['pointslug' =>  $slug, 'locale' => $locale])->first();
        if ($page === null) {
            $basepage = Page::where(['pointslug' =>  $slug])->first();
            if (!empty($basepage)) {
                $urlslug = $basepage->slug;
            } else {
                $urlslug = $slug;
            }
            $page = new Page();
            $page->slug = $urlslug;
            $page->pointslug = $slug;
        }

        //for slug
        if ($request->slugchange!='') {
            $slugchange = Str::slug($request->slugchange);
            $slugcount = Page::where('pointslug', '!=', $slug)->where(function ($query) use ($slugchange) {
                $query->where('pointslug', '=', $slugchange)->orWhere('slug', '=', $slugchange);
            })->count();
            
            if ($slugcount > 0) {
                return \Redirect::back()->withErrors(['Slug already exists']);
            }
            
            $page->slug = $slugchange;
            $page->save();

            //for draft slug
            $drPage = DraftPage::where(['pointslug' =>  $slug, 'locale' => $locale])->first();
            if (!empty($drPage)) {
                $drPage->slug = $slugchange;
                $drPage->save();
            }
            //for Arabic slug
            $arPage = Page::where(['pointslug' =>  $slug, 'locale' => 'ar'])->first();
            if (!empty($arPage)) {
                $arPage->slug = $slugchange;
                $arPage->save();
            }
            //for Draft Arabic slug
            $ardrPage = DraftPage::where(['pointslug' =>  $slug, 'locale' => 'ar'])->first();
            if (!empty($ardrPage)) {
                $ardrPage->slug = $slugchange;
                $ardrPage->save();
            }
            //for Russian slug
            $ruPage = Page::where(['pointslug' =>  $slug, 'locale' => 'ru'])->first();
            if (!empty($ruPage)) {
                $ruPage->slug = $slugchange;
                $ruPage->save();
            }
            //for Draft Russian slug
            $rudrPage = DraftPage::where(['pointslug' =>  $slug, 'locale' => 'ru'])->first();
            if (!empty($rudrPage)) {
                $rudrPage->slug = $slugchange;
                $rudrPage->save();
            }
            $message = array('message.level' => 'alert-success', 'message.content' => 'Slug Updated');
            return redirect('admin/page/edit/'.$page->pointslug)->with($message);
        }

        $message = array('message.level' => 'alert-success', 'message.content' => 'Slug Updated');
        return redirect()->back()->with($message);
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();
        $page = PageSection::where('page_id', $id)->delete();
        $message = array('message.level' => 'alert-success', 'message.content' => 'Page Deleted');
        return redirect()->back()->with($message);
    }

    public function destroysection($id)
    {
        $page = PageSection::find($id);
        $page->delete();
        $page = PageSection::where('parent', $id);
        $page->delete();
        $message = array('message.level' => 'alert-success', 'message.content' => 'Page Sectin Deleted');
        return redirect()->back()->with($message);
    }

    public function categoryindex()
    {
        $page = Pagecategory::orderBy('created_at', 'desc')->paginate(10);
        return view('Page::admin.category.index', ['page' => $page]);
    }

    public function categorycreate()
    {
        return view('Page::admin.category.create');
    }

    public function categorystore(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:3|max:100',
            'status' => 'required',
        ]);
        $page = new Pagecategory();

        $slug = Str::slug($request->title);
        sc:
        $slugcount = Pagecategory::where('slug', $slug)->count();
        if ($slugcount > 0) {
            $slug = $slug . '-1';
            goto sc;
        }

        $page->title = $request->title;
        $page->slug = $slug;
        $page->image = $request->image;
        $page->content = $request->content;
        $page->status = $request->status;

        $page->save();
        $message = array('message.level' => 'alert-success', 'message.content' => 'Page Created');
        return redirect('admin/pagecategory')->with($message);
    }

    public function categoryedit($id)
    {
        $page = Pagecategory::find($id);
        return view('Page::admin.category.edit', ['page' => $page]);
    }

    public function categoryupdate(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:3|max:100',
            'status' => 'required',
        ]);
        $page = Pagecategory::find($request->input('id'));


        $page->title = $request->title;
        $page->image = $request->image;
        $page->content = $request->content;
        $page->status = $request->status;

        $page->save();
        $message = array('message.level' => 'alert-success', 'message.content' => 'Page Updated');
        return redirect()->back()->with($message);
    }

    public function categorydestroy($id)
    {
        $page = Pagecategory::find($id);
        $category = $page['title'];
        $page->delete();
        $query = "UPDATE page SET category = REPLACE(category, '\"" . $category . "\"', '\"\"') WHERE UPPER(category) LIKE UPPER('%" . $category . "%')";
        DB::statement($query);

        $message = array('message.level' => 'alert-success', 'message.content' => 'Page Deleted');
        return redirect()->back()->with($message);
    }
}
