@extends('admin.layouts.app')
@push('stylesheets')

@endpush

@section('content')

<div class="container-fluid">
    @include('Gallery::admin.inc.alert')
    <div class="row contentTopMrg">

        <div class="col-12">
            <form action="{{ URL('admin/draftdesign/store') }}" method="post" id="designform">
                @csrf
                <div class="card customCard">

                    <div class="card-body pt-2">

                        <div class="form-group">
                            @include('Gallery::admin.inc.mediainput',['name'=>'image','sizemessage'=>'600 x 400'])
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control required" id="title" placeholder="Enter Title"
                                name="title" data-empty="Enter Title" data-max="250"
                                data-lengtherr="Maximum 250 characters allowed" />
                        </div>
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea class="form-control tinyarea" id="content" name="content"
                                placeholder="Enter Content" data-empty="Enter Content"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="title">Category</label>
                            <br>
                            @foreach($designcategory as $nkey => $nval)
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" value="{{$nval['title']}}"
                                        name="category[]" >{{$nval['title']}}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label for="title">Status</label>
                            <select class="form-control required" data-empty="Select Status" name="status" id="status">
                                <option value="">Select Status</option>
                                <option value="1" selected>Enable</option>
                                <option value="0">Disable</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Publish Date</label>
                            <input type="text" readonly class="form-control flatpicker required" id="publish_on"
                                placeholder="Enter Publish Date" name="publish_on" data-empty="Enter Publish Date" flat-config="current"/>
                        </div>
                    </div>


                </div>
                <hr class="sidebar-divider my-0">
                <div class="card customCard hidden">

                    <div class="card-body pt-2">
                        <h1>SEO Settings</h1>
                        <div class="form-group">
                            <label for="content">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description"
                                placeholder="Enter Meta Description" data-empty="Enter Meta Description" data-max="6000"
                                data-lengtherr="Maximum 6000 characters only allowed"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="content">Meta Keywords</label>
                            <textarea class="form-control" id="meta_keywords" name="meta_keywords"
                                placeholder="Enter Meta Keywords" data-empty="Enter Meta Keywords" data-max="6000"
                                data-lengtherr="Maximum 6000 characters only allowed"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="content">Analytics</label>
                            <textarea class="form-control" id="analytics" name="analytics"
                                placeholder="Enter Analytics" data-empty="Enter Analytics" data-max="6000"
                                data-lengtherr="Maximum 6000 characters only allowed"></textarea>
                        </div>
                    </div>


                </div>
                <hr>
                <button type="submit" class="btn btn-success pull-right"
                    onclick="if(validation(event,'#designform')==true) $('#designform').submit();">Save Draft</button> <a
                    href="{{URL()->previous()}}" class="btn btn-success">Back</a> 
            </form>
        </div>

    </div>
</div>
@include('Gallery::admin.inc.gallerymodal')
@endsection
@push('scripts')
@include('admin.inc.tinyscript')
<script>
    var root = $('#rootfolder').val();
</script>
@include('Gallery::admin.inc.mediascript',['name'=>'image'])
@endpush