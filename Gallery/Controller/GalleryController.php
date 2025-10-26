<?php

namespace App\Modules\Gallery\Controller;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Modules\Gallery\Models\Gallery;
use App\Modules\Gallery\Models\GalleryCategory;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{    public function ajaxload()
    {
        if (isset($_GET['limit'])) {
            $from = $_GET['limit'];
            $to = 12;
        } else {
            $from = 0;
            $to = 12;
        }
        $gallery = Gallery::skip($from)->take($to);
        $gallerycount = Gallery::query();
        if (isset($_GET['name']) && $_GET['name'] != '') {
            $gallery = $gallery->where('path', 'like', '%' . $_GET['name'] . '%');
            $gallerycount = $gallerycount->where('path', 'like', '%' . $_GET['name'] . '%');
        }
        if (isset($_GET['category']) && $_GET['category'] != '') {
            $gallery = $gallery->where('category', 'like', '%' . $_GET['category'] . '%');
            $gallerycount = $gallerycount->where('category', 'like', '%' . $_GET['category'] . '%');
        }
        if (isset($_GET['type']) && $_GET['type'] != '') {
            if ($_GET['type'] == 'image') {
                $gallery = $gallery->where(function ($query) {
                    $query->where('path', 'LIKE', '%png%')
                        ->orWhere('path', 'LIKE', '%jpg%')
                        ->orWhere('path', 'LIKE', '%jpeg%')
                        ->orWhere('path', 'LIKE', '%webp%')
                        ->orWhere('path', 'LIKE', '%svg%');
                });
                $gallerycount = $gallerycount->where(function ($query) {
                    $query->where('path', 'LIKE', '%png%')
                        ->orWhere('path', 'LIKE', '%jpg%')
                        ->orWhere('path', 'LIKE', '%jpeg%')
                        ->orWhere('path', 'LIKE', '%webp%')
                        ->orWhere('path', 'LIKE', '%svg%');
                });
            } else {
                $gallery = $gallery->where('path', 'like', '%' . $_GET['type'] . '%');
                $gallerycount = $gallerycount->where('path', 'like', '%' . $_GET['type'] . '%');
            }
        }
        $gallery = $gallery->orderBy('created_at', 'DESC')->get();
        $gallerycount = $gallerycount->count();
        return view('Gallery::admin.ajaxload', ['gallery' => $gallery, 'limit' => $from, 'gallerycount' => $gallerycount]);
    }

    public function create()
    {
        $from = 0;
        $to = $from + 12;
        $gallery = Gallery::skip($from)->take($to)->orderBy('created_at', 'DESC')->get();
        $gallerycount = Gallery::count();
        $gallerycategory = GalleryCategory::orderBy('created_at', 'DESC')->get();
        return view('Gallery::admin.create', ['gallery' => $gallery, 'limit' => $from, 'gallerycount' => $gallerycount, 'gallerycategory' => $gallerycategory]);
    }
    
    public function popup()
    {
        $from = 0;
        $to = $from + 12;
        $gallery = Gallery::skip($from)->take($to);
        $gallerycount = Gallery::query();
        if (isset($_GET['name']) && $_GET['name'] != '') {
            $gallery = $gallery->where('path', 'like', '%' . $_GET['name'] . '%');
            $gallerycount = $gallerycount->where('path', 'like', '%' . $_GET['name'] . '%');
        }
        if (isset($_GET['category']) && $_GET['category'] != '') {
            $gallery = $gallery->where('category', 'like', '%' . $_GET['category'] . '%');
            $gallerycount = $gallerycount->where('category', 'like', '%' . $_GET['category'] . '%');
        }
        if (isset($_GET['type']) && $_GET['type'] != '') {
            if ($_GET['type'] == 'image') {
                $gallery = $gallery->where(function ($query) {
                    $query->where('path', 'LIKE', '%png%')
                        ->orWhere('path', 'LIKE', '%jpg%')
                        ->orWhere('path', 'LIKE', '%jpeg%')
                        ->orWhere('path', 'LIKE', '%webp%')
                        ->orWhere('path', 'LIKE', '%svg%');
                });
                $gallerycount = $gallerycount->where(function ($query) {
                    $query->where('path', 'LIKE', '%png%')
                        ->orWhere('path', 'LIKE', '%jpg%')
                        ->orWhere('path', 'LIKE', '%jpeg%')
                        ->orWhere('path', 'LIKE', '%webp%')
                        ->orWhere('path', 'LIKE', '%svg%');
                });
            } else {
                $gallery = $gallery->where('path', 'like', '%' . $_GET['type'] . '%');
                $gallerycount = $gallerycount->where('path', 'like', '%' . $_GET['type'] . '%');
            }
        }
        $gallery = $gallery->orderBy('created_at', 'DESC')->get();
        $gallerycount = $gallerycount->count();

        $gallerycategory = GalleryCategory::orderBy('created_at', 'DESC')->get();
        return view('Gallery::admin.gallerypopup', ['gallery' => $gallery, 'limit' => $from, 'gallerycount' => $gallerycount, 'gallerycategory' => $gallerycategory]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,png,svg,webp,pdf,docx,mp4|max:100014',
            'category' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $return = array('success' => 0, 'message' => $error);
            return \Response::json($return);
        }

        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                $file = $request->file('file');
                $filename = ($request->name != '') ? $request->name : time();
                $checkfilename = Gallery::where('path', $filename . '.' . $file->getClientOriginalExtension())->count();
                if ($checkfilename > 0) {
                    $return = array('success' => 0, 'message' => 'Image name already exists.');
                    return \Response::json($return);
                }
                $name = $filename . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('/gallery');
                $file->move($destinationPath, $name);
                $data['path'] = $name;
                $data['category'] = $request->category;
                Gallery::create($data);
                $return = array('success' => 1, 'message' => 'Gallery Added');
                return \Response::json($return);
            }
            abort(500, 'Could not upload image :(');
        }
    }
    public function storecategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $return = array('success' => 0, 'message' => $error);
            return \Response::json($return);
        }

        $data['category'] = $request->category;
        GalleryCategory::updateOrCreate($data);
        $return = array('success' => 1, 'message' => 'Category Added');
        return \Response::json($return);
    }

    public function ajaxloadcategory(){
        $gallerycategory = GalleryCategory::orderBy('category','ASC')->get();
        return view('Gallery::admin.ajaxloadcategory',['gallerycategory' => $gallerycategory]);
    }

    public function ajaxinput($name="image"){
        return view('Gallery::admin.inc.mediainput',['name'=>$name,'class'=>'required','attr'=>'data-empty=Select&nbsp;&nbsp;Image']);
    }
    
    public function category(){
        $gallerycategory = GalleryCategory::orderBy('category','ASC');
        if (isset($_GET['search']) && $_GET['search'] != '') {
            $gallerycategory = $gallerycategory->where('category', 'like', '%' . $_GET['search'] . '%');
        }
        $gallerycategory = $gallerycategory->paginate('10');
        return view('Gallery::admin.category',['gallerycategory' => $gallerycategory]);
    }
    
    public function addcategory(){
        return view('Gallery::admin.addcategory');
    }

    public function deletecategory($id){
        $res=GalleryCategory::where('id',$id)->delete();
        $message = array('message.level' => 'alert-success', 'message.content' => 'Category Deleted');
        return redirect()->back()->with($message);
    }

    public function show($id)
    {
        return view('Gallery::admin.show');
    }

    public function edit($id)
    {
        return view('Gallery::admin.edit');
    }

    public function destroy($id)
    {
        $gallery = Gallery::find($id);
        $filename = public_path('gallery/' . $gallery->path);
        if (\File::exists($filename)) {
            rename(public_path('gallery/' . $gallery->path), public_path('gallery/removed_' . time() . '_' . $gallery->path));
        }
        $gallery->delete();
        $return = array('success' => 1, 'message' => 'Gallery Item Deleted');
        return \Response::json($return);
    }

    public function categorysuggestion(Request $request)
    {
        $category = GalleryCategory::where('category', '!=', '');
        if ($request->value)
            $category = $category->where('category', 'like', '%' . $request->value . '%');
        $category = $category->groupBy('category')->pluck('category');
        return view('Gallery::admin.category.suggestion', ['category' => $category]);
    }
}
