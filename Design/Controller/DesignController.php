<?php

namespace App\Modules\Design\Controller;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Design\Models\Design;
use App\Modules\Design\Models\Designcategory;
use Illuminate\Support\Str;
use DB;

class DesignController extends Controller
{
    public function index()
    {
        $designs = Design::with('draft')->orderBy('created_at', 'desc');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $designs = $designs->where(function ($query){
                $query->where('title', 'like', '%' . $_GET['search'] . '%')->orWhere('slug', 'like', '%' . $_GET['search'] . '%');
            });
        }
        if (isset($_GET['lang']) && $_GET['lang'] != '') {
            $designs = $designs->where('locale', $_GET['lang']);
        } else {
            $designs = $designs->where('locale', 'en');
        }
        $designs = $designs->paginate(10);
        return view('Design::admin.index', ['designs' => $designs]);
    }

    public function create()
    {
        $data['designcategory'] = Designcategory::active()->orderBy('title', 'ASC')->get()->toArray();
        return view('Design::admin.create', $data);
    }

    public function store(Request $request)
    {
        $locale = app()->getLocale();
        
        $validatedData = $request->validate([
            'title' => 'required|max:250',
            'meta_description' => 'max:6000',
            'meta_keywords' => 'max:6000',
            'analytics' => 'max:6000',
            'status' => 'required'
        ]);
        $design = new Design();

        $slug = Str::slug($request->title);
        sc:
        $slugcount = Design::where('slug', $slug)->count();
        if ($slugcount > 0) {
            $slug = $slug . '-1';
            goto sc;
        }
        $design->title = $request->title;
        $design->slug = $slug;
        $design->locale = $locale;
        $design->pointslug = $slug;
        $design->image = $request->image;
        $design->content = $request->content;
        $design->category = (!empty($request->category))?json_encode($request->category):'[]';
        $design->meta_description = $request->meta_description;
        $design->meta_keywords = $request->meta_keywords;
        $design->analytics = $request->analytics;
        $design->status = $request->status;

        $design->save();

        $message = array('message.level' => 'alert-success', 'message.content' => 'Design Created');
        return redirect('admin/design')->with($message);
    }

    public function edit($pointslug)
    {
        $locale = app()->getLocale();
        $design = Design::where('pointslug', $pointslug)->where('locale', $locale)->first();     
        $designcategory = Designcategory::active()->orderBy('title', 'ASC')->get()->toArray();
        return view('Design::admin.edit', ['design' => $design, 'slug' => $pointslug, 'designcategory' => $designcategory]);
    }

    public function update(Request $request)
    {
        $locale = app()->getLocale();
        $validatedData = $request->validate([
            'title' => 'required|max:250',
            'meta_description' => 'max:6000',
            'meta_keywords' => 'max:6000',
            'analytics' => 'max:6000',
            'status' => 'required'
        ]);
        $slug = $request->input('slug');
        $design = Design::where(['pointslug' =>  $slug, 'locale' => $locale])->first();
        if ($design === null) {
            $design = new design();
            $design->slug = $slug;
            $design->pointslug = $slug;
        }

        //for slug
        if($request->slugchange!='')
        {
            $slugchange = Str::slug($request->slugchange);
            $slugcount = Design::where('pointslug','!=',$slug)->where(function ($query) use ($slugchange){
                $query->where('pointslug','=',$slugchange)->orWhere('slug','=',$slugchange);
            })->count();
            
            if ($slugcount > 0) {
                return \Redirect::back()->withErrors(['Slug already exists']);
            }
            
            $design->slug = $slugchange;
            $design->save();
            return redirect('admin/design/edit/'.$design->pointslug)->with(['Slug Updated']);
        }

        $design->title = $request->title;
        $design->locale = $locale;
        $design->image = $request->image;
        $design->content = $request->content;
        $design->category = (!empty($request->category))?json_encode($request->category):'[]';
        $design->meta_description = $request->meta_description;
        $design->meta_keywords = $request->meta_keywords;
        $design->analytics = $request->analytics;
        $design->status = $request->status;

        $design->save();

        if(isset($_GET['fromdraft'])){
            $message = array('message.level' => 'alert-success', 'message.content' => 'Design Updated', 'redirect' => URL('admin/design'));
            return response()->json($message);
        }
        else{
            $message = array('message.level' => 'alert-success', 'message.content' => 'Design Updated');
            return redirect()->back()->with($message);
        }
    }

    public function destroy($id)
    {
        $design = Design::find($id);
        $design->delete();
        $message = array('message.level' => 'alert-success', 'message.content' => 'Design Deleted');
        return redirect()->back()->with($message);
    }

    public function loaddesign(Request $request){
        $allowed_ar = array_filter(explode(',',$request->allowed));
        $design = Design::orderBy('title','ASC');
        if(isset($request->search) && $request->search!=''){
            $design = $design->where('title','like','%'.$request->search.'%');
        }
        if(isset($request->category) && $request->category!=''){
            $design = $design->where('category','like','%"'.$request->category.'"%');
        }
        if(!empty($allowed_ar)){
            foreach($allowed_ar as $alv){
                $design = $design->where('category','like','%"'.$alv.'"%');
            }
        }
        $design = $design->paginate(5);
        $data['design'] = $design;
        $data['designcategory'] = Designcategory::orderBy('title','ASC')->get();
        $data['request'] = $request;
        return view('Design::admin.designload', $data);
    }

    public function categoryindex()
    {
        $design = Designcategory::orderBy('created_at', 'desc')->paginate(10);
        return view('Design::admin.category.index', ['design' => $design]);
    }

    public function categorycreate()
    {
        return view('Design::admin.category.create');
    }

    public function categorystore(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:3|max:100',
            'status' => 'required',
        ]);
        $design = new Designcategory();

        $slug = Str::slug($request->title);
        sc:
        $slugcount = Designcategory::where('slug', $slug)->count();
        if ($slugcount > 0) {
            $slug = $slug . '-1';
            goto sc;
        }

        $design->title = $request->title;
        $design->slug = $slug;
        $design->image = $request->image;
        $design->content = $request->content;
        $design->status = $request->status;

        $design->save();
        $message = array('message.level' => 'alert-success', 'message.content' => 'Design Created');
        return redirect('admin/designcategory')->with($message);
    }

    public function categoryedit($id)
    {
        $design = Designcategory::find($id);
        return view('Design::admin.category.edit', ['design' => $design]);
    }

    public function categoryupdate(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:3|max:100',
            'status' => 'required',
        ]);
        $design = Designcategory::find($request->input('id'));

        $design->title = $request->title;
        $design->image = $request->image;
        $design->content = $request->content;
        $design->status = $request->status;

        $design->save();
        $message = array('message.level' => 'alert-success', 'message.content' => 'Design Updated');
        return redirect()->back()->with($message);
    }

    public function categorydestroy($id)
    {
        $design = Designcategory::find($id);
        $category = $design['title'];
        $design->delete();
        $query = "UPDATE design SET category = REPLACE(category, '\"" . $category . "\"', '\"\"') WHERE UPPER(category) LIKE UPPER('%" . $category . "%')";
        DB::statement($query);

        $message = array('message.level' => 'alert-success', 'message.content' => 'Design Deleted');
        return redirect()->back()->with($message);
    }
}
