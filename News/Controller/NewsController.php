<?php

namespace App\Modules\News\Controller;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\News\Models\News;
use App\Modules\News\Models\NewsSection;
use App\Modules\News\Models\Newscategory;
use App\Modules\News\Models\DraftNews;
use Illuminate\Support\Str;
use Auth;
use DB;

class NewsController extends Controller
{
    public function index()
    {
        $newss = News::with('draft')->orderBy('created_at', 'desc');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $newss = $newss->where(function ($query) {
                $query->where('title', 'like', '%' . $_GET['search'] . '%')->orWhere('slug', 'like', '%' . $_GET['search'] . '%');
            });
        }
        if (isset($_GET['lang']) && $_GET['lang'] != '') {
            $newss = $newss->where('locale', $_GET['lang']);
        } else {
            $newss = $newss->where('locale', 'en');
        }
        $newss = $newss->paginate(10);
        return view('News::admin.index', ['newss' => $newss]);
    }

    public function create()
    {
        $data['newscategory'] = Newscategory::active()->orderBy('title', 'ASC')->get()->toArray();
        $news['pointslug'] = 'new';
        //adding default config to all
        $configar = config('news.section.' . $news['pointslug']);
        foreach (config('news.section.default') as $confdk => $confdv) {
            $configar[$confdk] = $confdv;
        }
        config(['news.section.' . $news['pointslug'] => $configar]);
        //end
        $data['news'] = $news;
        $data['slug'] = 'new';
        return view('News::admin.create', $data);
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
        $news = new News();

        $slug = Str::slug($request->title);
        sc:
        $slugcount = News::where('slug', $slug)->count();
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
        $news->category = (!empty($request->category)) ? json_encode($request->category) : '[]';
        $news->meta_description = $request->meta_description;
        $news->meta_keywords = $request->meta_keywords;
        $news->analytics = $request->analytics;
        $news->status = $request->status;
        $news->publish_on = date('Y-m-d H:i:s', strtotime($request->publish_on));
        $news->created_by = Auth::user()->id;

        $news->save();

        $configar = config('news.section.' . $news['pointslug']);
        foreach (config('news.section.default') as $confdk => $confdv) {
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
                        if (isset($v['slug']) && $v['slug'] == $fk) {
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
                    NewsSection::updateOrCreate($match, $data);
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
                            if (isset($v['slug']) && $v['slug'] == $fk) {
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
                        if (config('news.section.' . $slug . '.' . $k . '.parent')) {
                            $data['parent'] = $request->input($k . config('news.section.' . $slug . '.' . $k . '.parent') . $i);
                        }
                        NewsSection::updateOrCreate($match, $data);
                    }
                }
            }
        }

        $message = array('message.level' => 'alert-success', 'message.content' => 'News Created');
        return redirect('admin/news')->with($message);
    }

    public function show($id)
    {
        return view('News::admin.show');
    }

    public function edit($pointslug)
    {
        $locale = app()->getLocale();
        $news = News::with('sections')->where('pointslug', $pointslug)->where('locale', $locale)->first();
        $newssections = [];
        //adding default config to all
        $configar = config('news.section.' . $pointslug);
        foreach (config('news.section.default') as $confdk => $confdv) {
            if ($confdk == 'content' && config('news.hide-content.' . $pointslug)) {
                continue;
            }
            $configar[$confdk] = $confdv;
        }
        config(['news.section.' . $pointslug => $configar]);
        //end
        if (!empty($news) && config('news.section.' . $pointslug)) {
            foreach (config('news.section.' . $pointslug) as $k => $v) {
                $newssections[$k] = NewsSection::with('children')->where('news_id', $news->id)->where('type', $k)->orderBy('order', 'ASC')->get()->toArray();
            }
        }
        $newscategory = Newscategory::active()->orderBy('title', 'ASC')->get()->toArray();
        return view('News::admin.edit', ['news' => $news, 'newssections' => $newssections, 'slug' => $pointslug, 'newscategory' => $newscategory]);
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
        $news = News::where(['pointslug' =>  $slug, 'locale' => $locale])->first();
        if ($news === null) {
            $basenews = News::where(['pointslug' =>  $slug])->first();
            if (!empty($basenews)) {
                $urlslug = $basenews->slug;
            } else {
                $urlslug = $slug;
            }
            $news = new News();
            $news->slug = $urlslug;
            $news->pointslug = $slug;
        }

        //for slug
        if ($request->slugchange != '') {
            $slugchange = Str::slug($request->slugchange);
            $slugcount = News::where('pointslug', '!=', $slug)->where(function ($query) use ($slugchange) {
                $query->where('pointslug', '=', $slugchange)->orWhere('slug', '=', $slugchange);
            })->count();

            if ($slugcount > 0) {
                return \Redirect::back()->withErrors(['Slug already exists']);
            }

            $news->slug = $slugchange;
            $news->save();

            //for draft slug
            $drNews = DraftNews::where(['pointslug' =>  $slug, 'locale' => $locale])->first();
            if (!empty($drNews)) {
                $drNews->slug = $slugchange;
                $drNews->save();
            }
            //for Arabic slug
            $arNews = News::where(['pointslug' =>  $slug, 'locale' => 'ar'])->first();
            if (!empty($arNews)) {
                $arNews->slug = $slugchange;
                $arNews->save();
            }
            //for Draft Arabic slug
            $ardrNews = DraftNews::where(['pointslug' =>  $slug, 'locale' => 'ar'])->first();
            if (!empty($ardrNews)) {
                $ardrNews->slug = $slugchange;
                $ardrNews->save();
            }
            //for Russian slug
            $ruNews = News::where(['pointslug' =>  $slug, 'locale' => 'ru'])->first();
            if (!empty($ruNews)) {
                $ruNews->slug = $slugchange;
                $ruNews->save();
            }
            //for Draft Russian slug
            $rudrNews = DraftNews::where(['pointslug' =>  $slug, 'locale' => 'ru'])->first();
            if (!empty($rudrNews)) {
                $rudrNews->slug = $slugchange;
                $rudrNews->save();
            }
            return redirect('admin/news/edit/' . $news->pointslug)->with(['Slug Updated']);
        }

        $news->title = $request->title;
        $news->locale = $locale;
        $news->image = $request->image;
        $news->content = $request->content;
        $news->category = (!empty($request->category)) ? json_encode($request->category) : '[]';
        $news->meta_description = $request->meta_description;
        $news->meta_keywords = $request->meta_keywords;
        $news->analytics = $request->analytics;
        $news->status = $request->status;
        $news->publish_on = date('Y-m-d H:i:s', strtotime($request->publish_on));
        $news->created_by = Auth::user()->id;

        $news->save();

        $configar = config('news.section.' . $news['pointslug']);
        foreach (config('news.section.default') as $confdk => $confdv) {
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
                        if (isset($v['slug']) && $v['slug'] == $fk) {
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
                    NewsSection::updateOrCreate($match, $data);
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
                            if (isset($v['slug']) && $v['slug'] == $fk) {
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
                        if (config('news.section.' . $slug . '.' . $k . '.parent')) {
                            $data['parent'] = $request->input($k . config('news.section.' . $slug . '.' . $k . '.parent') . $i);
                        }
                        NewsSection::updateOrCreate($match, $data);
                    }
                }
            }
        }

        if (isset($_GET['fromdraft'])) {
            $message = array('message.level' => 'alert-success', 'message.content' => 'News Updated', 'redirect' => URL('admin/news'));
            return response()->json($message);
        } else {
            $message = array('message.level' => 'alert-success', 'message.content' => 'News Updated');
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

        $news = News::where(['pointslug' =>  $slug, 'locale' => $locale])->first();
        if ($news === null) {
            $basenews = News::where(['pointslug' =>  $slug])->first();
            if (!empty($basenews)) {
                $urlslug = $basenews->slug;
            } else {
                $urlslug = $slug;
            }
            $news = new News();
            $news->slug = $urlslug;
            $news->pointslug = $slug;
        }

        //for slug
        if ($request->slugchange != '') {
            $slugchange = Str::slug($request->slugchange);
            $slugcount = News::where('pointslug', '!=', $slug)->where(function ($query) use ($slugchange) {
                $query->where('pointslug', '=', $slugchange)->orWhere('slug', '=', $slugchange);
            })->count();

            if ($slugcount > 0) {
                return \Redirect::back()->withErrors(['Slug already exists']);
            }

            $news->slug = $slugchange;
            $news->save();

            //for draft slug
            $drNews = DraftNews::where(['pointslug' =>  $slug, 'locale' => $locale])->first();
            if (!empty($drNews)) {
                $drNews->slug = $slugchange;
                $drNews->save();
            }
            //for Arabic slug
            $arNews = News::where(['pointslug' =>  $slug, 'locale' => 'ar'])->first();
            if (!empty($arNews)) {
                $arNews->slug = $slugchange;
                $arNews->save();
            }
            //for Draft Arabic slug
            $ardrNews = DraftNews::where(['pointslug' =>  $slug, 'locale' => 'ar'])->first();
            if (!empty($ardrNews)) {
                $ardrNews->slug = $slugchange;
                $ardrNews->save();
            }
            //for Russian slug
            $ruNews = News::where(['pointslug' =>  $slug, 'locale' => 'ru'])->first();
            if (!empty($ruNews)) {
                $ruNews->slug = $slugchange;
                $ruNews->save();
            }
            //for Draft Russian slug
            $rudrNews = DraftNews::where(['pointslug' =>  $slug, 'locale' => 'ru'])->first();
            if (!empty($rudrNews)) {
                $rudrNews->slug = $slugchange;
                $rudrNews->save();
            }
            $message = array('message.level' => 'alert-success', 'message.content' => 'Slug Updated');
            return redirect('admin/news/edit/' . $news->pointslug)->with($message);
        }

        $message = array('message.level' => 'alert-success', 'message.content' => 'Slug Updated');
        return redirect()->back()->with($message);
    }

    public function destroy($id)
    {
        $news = News::find($id);
        $news->delete();
        $news = NewsSection::where('news_id', $id)->delete();
        $message = array('message.level' => 'alert-success', 'message.content' => 'News Deleted');
        return redirect()->back()->with($message);
    }

    public function destroysection($id)
    {
        $news = NewsSection::find($id);
        $news->delete();
        $news = NewsSection::where('parent', $id);
        $news->delete();
        $message = array('message.level' => 'alert-success', 'message.content' => 'News Sectin Deleted');
        return redirect()->back()->with($message);
    }

    public function categoryindex()
    {
        $news = Newscategory::orderBy('created_at', 'desc')->paginate(10);
        return view('News::admin.category.index', ['news' => $news]);
    }

    public function categorycreate()
    {
        return view('News::admin.category.create');
    }

    public function categorystore(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:3|max:100',
            'status' => 'required',
        ]);
        $news = new Newscategory();

        $slug = Str::slug($request->title);
        sc:
        $slugcount = Newscategory::where('slug', $slug)->count();
        if ($slugcount > 0) {
            $slug = $slug . '-1';
            goto sc;
        }

        $news->title = $request->title;
        $news->slug = $slug;
        $news->image = $request->image;
        $news->content = $request->content;
        $news->status = $request->status;

        $news->save();
        $message = array('message.level' => 'alert-success', 'message.content' => 'News Created');
        return redirect('admin/newscategory')->with($message);
    }

    public function categoryedit($id)
    {
        $news = Newscategory::find($id);
        return view('News::admin.category.edit', ['news' => $news]);
    }

    public function categoryupdate(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:3|max:100',
            'status' => 'required',
        ]);
        $news = Newscategory::find($request->input('id'));


        $news->title = $request->title;
        $news->image = $request->image;
        $news->content = $request->content;
        $news->status = $request->status;

        $news->save();
        $message = array('message.level' => 'alert-success', 'message.content' => 'News Updated');
        return redirect()->back()->with($message);
    }

    public function categorydestroy($id)
    {
        $news = Newscategory::find($id);
        $category = $news['title'];
        $news->delete();
        $query = "UPDATE news SET category = REPLACE(category, '\"" . $category . "\"', '\"\"') WHERE UPPER(category) LIKE UPPER('%" . $category . "%')";
        DB::statement($query);

        $message = array('message.level' => 'alert-success', 'message.content' => 'News Deleted');
        return redirect()->back()->with($message);
    }
}
