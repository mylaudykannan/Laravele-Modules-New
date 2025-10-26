@extends('admin.layouts.app')

@section('content')
@include('Design::admin.inc.alert')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card customCard my-4">
                <!-- Card header -->
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                      <h5 class="text-white text-capitalize pl-3 mb-0">Add Category</h5>
                      
                    </div>
                </div>
                <div class="card-body pt-2">
                    <form action="{{ URL('admin/designcategory/store') }}" method="post" id="designform">
                        @csrf
                        <div class="form-group">
                            @include('Gallery::admin.inc.mediainput',['name'=>'image'])
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control required" id="title" placeholder="Enter Title"
                                name="title" data-empty="Enter Title" />
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content"
                                placeholder="Enter Content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="title">Status</label>
                            <select class="form-control required" data-empty="Select Status" name="status" id="status">
                                <option value="">Select Status</option>
                                <option value="Enable" selected>Enable</option>
                                <option value="Disable">Disable</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"
                            onclick="if(validation(event,'#designform')==true) $('#designform').submit();">Submit</button>&nbsp;&nbsp;<a
                            href="{{URL('admin/design')}}" class="btn btn-primary">Back</a>
                    </form>
                </div>


            </div>

        </div>
    </div>
</div>
@include('Gallery::admin.inc.gallerymodal')
@endsection
@push('scripts')
@include('Gallery::admin.inc.mediascript',['name'=>'image'])
@include('admin.inc.tinyscript')
@endpush