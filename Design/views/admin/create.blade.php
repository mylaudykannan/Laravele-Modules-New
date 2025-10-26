@extends('admin.layouts.app')
@push('stylesheets')
@endpush

@section('content')

<div class="container-fluid">
    @include('Gallery::admin.inc.alert')
    <div class="row">

        <div class="col-12">
            <form action="{{ URL('admin/design/store') }}" method="post" id="designform">
                @csrf
                <div class="card customCard mt-4 mb-5">
                    <!-- Card header -->
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                          <h5 class="text-white text-capitalize pl-3 mb-0">Add New Design</h5>
                        </div>
                    </div>
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
                            <textarea class="form-control tinyarea required" id="content" name="content"
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
                    </div>
                </div>

                <div class="card customCard hidden">
                    <!-- Card header -->
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                          <h5 class="text-white text-capitalize pl-3 mb-0">SEO Settings</h5>
                        </div>
                    </div>
                    <div class="card-body pt-2">
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
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <a href="{{URL()->previous()}}" class="btn btn-default">Back</a>
                    </div>
                    <div class="col-12 col-sm-6 d-flex justify-content-end">
                        <button type="submit" class="btn btn-success" id="draftsubmitbtn">Save as Draft</button>
                        <button type="submit" class="btn btn-success ml-3" onclick="if(validation(event,'#designform')==true) $('#designform').submit();">Publish</button>
                    </div>
                </div>
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
    $('body').on('click','#draftsubmitbtn',function(e){
        e.preventDefault();
        var _this = $(this);
        _this.prop('disabled', true);
        var result = $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: root + '/admin/draftdesign/store?fromdesign=1',
            type: "POST",
            datatype: "json",
            data: new FormData($('#designform')[0]),
            contentType: false,
            cache: false,
            processData: false,
            async: false,
            success: function(data) {
                _this.prop('disabled', false);
                window.location.href = data.redirect;
            },
            error: function(xhr, status, error) {
                _this.prop('disabled', false);
                var result = eval("(" + xhr.responseText + ")");
                $.each(result.errors, function(key, value) {
                    var keyname = key;
                    key = key.replace(".", "");
                    var id = "#" + key + "-error";
                    var error_lable = '<label id="' + key + '-error" class="error" for="' + key + '" style="">'+ value +'</label>';
                    $('[name="' + keyname + '"]').after(error_lable);
                });                
                return true;
            }
        });    
    });
</script>
@include('Gallery::admin.inc.mediascript',['name'=>'image'])
@endpush