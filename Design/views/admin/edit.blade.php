@extends('admin.layouts.app')

@push('stylesheets')

<style>
</style>
@if(!empty($design) && config('design.view.'.$design['pointslug']))
<style>
    #editdiv{
        display: none;
    }
    .transparent:after {
        content: '';
        position: absolute;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        background: #ffffffc2;
        z-index: 999999;
    }    
    #viewdiv{
        position: absolute;
        top: 100px;
        right: 20px;
        width: 85%;
    }
    .vieweditbtn{
        color: #ff0000;
        background: #fff;
        cursor: pointer;
        float: right;
        z-index: 999999;
        position: relative;
    }
</style>
@endif
@endpush
@section('content')
<div class="container-fluid" id="editdiv">
    @include('Gallery::admin.inc.alert')
    <div class="row">
        <div class="col-12">
            <form action="{{ URL('admin/design/update?lang='.app()->getLocale()) }}" method="post" id="designform">
                @csrf
                <div class="card customCard mt-4 mb-5" id="titlecard">
                    <!-- Card header -->
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                          <h5 class="text-white text-capitalize pl-3 mb-0">Edit Design</h5>
                          <a href="{{URL('admin/design/add')}}" class="btn btn-default mb-0 mr-3 btn-sm text-white">ADD NEW DESIGN</a>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="form-group">
                            @include('Gallery::admin.inc.mediainput',['name'=>'image','edit'=>true,'value'=>$design['image'] ?? '','sizemessage'=>'600 x 400'])
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control required" id="title" placeholder="Enter Title"
                                name="title" value="{{$design['title'] ?? ''}}" data-empty="Enter Title" data-max="250"
                                data-lengtherr="Maximum 250 characters allowed" />
                        </div>
                        <div class="form-group @if(config('design.hidecontent.'.$slug)) hidden @endif">
                            <label for="content">Content</label>
                            <textarea class="form-control tinyarea required" id="content" name="content"
                                placeholder="Enter Content" data-empty="Enter Content">{{$design['content'] ?? ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="title">Category</label>
                            <br>
                            <?php 
                            $categories = (isset($design['category']) && $design['category']!=null)?json_decode($design['category'],true):[];
                            ?>
                            @foreach($designcategory as $nkey => $nval)
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" value="{{$nval['title']}}" name="category[]"
                                        @if(in_array($nval['title'],$categories)) checked @endif>{{$nval['title']}}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label for="title">Status</label>
                            <select class="form-control required" data-empty="Select Status" name="status" id="status">
                                <option value="">Select Status</option>
                                <option value="1" @if(isset($design['status']) && $design['status']=='1' ) selected
                                    @endif>Enable</option>
                                <option value="0" @if(isset($design['status']) && $design['status']=='0' ) selected
                                    @endif>Disable</option>
                            </select>
                        </div>
                        <input type="hidden" name="slug" value="{{$slug}}" />
                    </div>
                </div>
                
                <div class="d-flex submitbtndiv">
                    @include('admin.inc.updatenotes',['form'=>'#designform'])
                    <a href="{{URL('admin/design')}}" class="btn btn-success ml-3 mb-0">Back</a> 
                    <button type="submit" class="btn btn-success  ml-3 mb-0" id="draftsubmitbtn">Save as Draft</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('Gallery::admin.inc.gallerymodal')
@endsection
@push('scripts')
@include('admin.inc.addsectionscript')
@include('Gallery::admin.inc.mediascript',['name'=>'image'])
<script>
    var root = $('#rootfolder').val();
    var locale = '<?php echo app()->getLocale();?>';
    $('body').on('click','#draftsubmitbtn',function(e){
        e.preventDefault();
        var _this = $(this);
        _this.prop('disabled', true);
        var result = $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: root + '/admin/draftdesign/update?fromdesign&lang='+locale,
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
    function showedit(element,innerelement=''){
        $('#viewdiv').css('display','none');
        $('#editdiv').css('display','block');
        if($(element).hasClass('transparent')){
            $(element).removeClass('transparent');
            $(element).attr("tabindex",-1).focus();
        }
        else{
            $(element).closest('.transparent').attr("tabindex",-1).focus();
            $(element).closest('.transparent').removeClass('transparent');            
        }
        if(innerelement!=''){
            $(innerelement).attr("tabindex",-2).focus();
            $(innerelement).prevAll().addClass('transparent');
            $(innerelement).nextAll().addClass('transparent');
        }
    }
</script>
@endpush
@include('admin.inc.tinyscript')