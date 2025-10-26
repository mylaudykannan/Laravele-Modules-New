<?php

namespace App\Modules\Design\Controller;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Design\Models\DesignCategory;
use App\Modules\Design\Models\DraftDesign;
use Illuminate\Support\Str;

class DraftDesignController extends Controller
{
    
    public function index()
    {
        $designs = DraftDesign::orderBy('created_at', 'desc');
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
        return view('Design::admin.draft.index', ['designs' => $designs]);
    }

    public function create()
    {
        $data['designcategory'] = Designcategory::active()->orderBy('title', 'ASC')->get()->toArray();
        return view('Design::admin.draft.create', $data);
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
        $design = new DraftDesign();

        $slug = Str::slug($request->title);
        sc:
        $slugcount = DraftDesign::where('slug', $slug)->count();
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
        $design->meta_description = $request->meta_description;
        $design->meta_keywords = $request->meta_keywords;
        $design->analytics = $request->analytics;
        $design->status = $request->status;

        $design->save();

        if(isset($_GET['fromdesign'])){
            $message = array('message.level' => 'alert-success', 'message.content' => 'Design Created', 'redirect' => URL('admin/design'));
            return response()->json($message);
        }
        else{
            $message = array('message.level' => 'alert-success', 'message.content' => 'Design Created');
            return redirect('admin/draftdesign')->with($message);
        }
    }

    public function edit($pointslug)
    {
        $locale = app()->getLocale();
        $design = DraftDesign::where('pointslug', $pointslug)->where('locale', $locale)->first();
        $designcategory = Designcategory::active()->orderBy('title', 'ASC')->get()->toArray();
        return view('Design::admin.draft.edit', ['design' => $design, 'slug' => $pointslug, 'designcategory' => $designcategory]);
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
        $design = DraftDesign::where(['slug' =>  $slug, 'locale' => $locale])->first();
        if ($design === null) {
            $design = new DraftDesign();
            $design->slug = $slug;
            $design->pointslug = $slug;
        }
        $design->title = $request->title;
        $design->locale = $locale;
        $design->image = $request->image;
        $design->content = $request->content;
        $design->meta_description = $request->meta_description;
        $design->meta_keywords = $request->meta_keywords;
        $design->analytics = $request->analytics;
        $design->status = $request->status;

        $design->save();

        if(isset($_GET['fromdesign'])){
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
        $design = DraftDesign::find($id);
        $design->delete();

        $message = array('message.level' => 'alert-success', 'message.content' => 'Design Deleted');
        return redirect()->back()->with($message);
    }
}
