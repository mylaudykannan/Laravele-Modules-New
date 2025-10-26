@extends('admin.layouts.app')

@section('content')
@include('Design::admin.inc.alert')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card customCard my-4">

                <div class="card-body pt-2">

                    <form action="{{ URL('admin/designcategory/update') }}" method="post" id="designform">
                        @csrf
                        <div class="form-group">
                            @include('Gallery::admin.inc.mediainput',['name'=>'image','edit'=>true,'value'=>$design['image']])
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control required" id="title" placeholder="Enter Title"
                                name="title" value="{{$design['title']}}" data-empty="Enter Title" />
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content"
                                placeholder="Enter Content">{{$design['content']}}</textarea>
                        </div>
                <div class="form-group">
                    <label for="title">Status</label>
                    <select class="form-control required" data-empty="Select Status" name="status" id="status">
                        <option value="">Select Status</option>
                        <option value="Enable" @if(isset($design['status']) && $design['status']=='Enable' ) selected
                            @endif>Enable</option>
                        <option value="Disable" @if(isset($design['status']) && $design['status']=='Disable' ) selected
                            @endif>Disable</option>
                    </select>
                </div>
                <input type="hidden" name="id" value="{{$design['id']}}" />
                <button type="submit" class="btn btn-primary"
                    onclick="if(validation(event,'#designform')==true) $('#designform').submit();">Submit</button>&nbsp;&nbsp;<a
                    href="{{URL('admin/designcategory')}}" class="btn btn-primary">Back</a>
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
@endpush