@extends('admin.layouts.app')

@push('stylesheets')

<style>
    .element {
        white-space: pre-line;
        position: relative;
    }
    .moveupbtn,.movedownbtn{
        float: left;
        font-size: 30px;
        margin: 10px 0px;
        cursor: pointer;
        width: 100%;
        text-align: center;
    }
    .table-sortable tbody tr:nth-child(2) .moveupbtn{
        display: none;
    }
    .table-sortable tbody tr:last-child .movedownbtn{
        display: none;
    }
</style>
@switch($slug)
@case('home')
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/home.css') }}">
@case('patient')
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/patient.css') }}">
@case('dhca-governance-laws')
<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/regulations.css') }}">
@endswitch
<link rel="stylesheet" type="text/css" href="{{ asset('css/gallery.css') }}">
@endpush
@section('content')
<div class="container-fluid">
    @include('Gallery::admin.inc.alert')
    <div class="row contentTopMrg">
        <div class="col-12">
            <form action="{{ URL('admin/draftpage/update?lang='.app()->getLocale()) }}" method="post" id="pageform">
                @csrf
                <div class="card customCard my-4">
                    <!-- Card header -->
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                          <h5 class="text-white text-capitalize pl-3 mb-0">Edit Draft Page</h5>
                          <a href="{{URL('admin/draftpage/add')}}" class="btn btn-default mb-0 mr-3 btn-sm text-white">ADD NEW DRAFT</a>
                        </div>
                    </div>

                    <div class="card-body pt-2">
                        <?php $previewurl = (config('page.url.'.$slug))?URL(config('page.url.'.$slug).'?preview=1'):URL('page/'.$slug.'?preview=1');?>
                        <a href="{{$previewurl}}" class="btn btn-sm btn-info float-right" target="_blank">Preview</a>

                        <div class="form-group">
                            @include('Gallery::admin.inc.mediainput',['name'=>'image','edit'=>true,'value'=>$page['image'] ?? '','class' => 'required','attr' => 'data-empty=Select&nbsp;Image','sizemessage'=>'600 x 400','showalt'=>true])
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control required" id="title" placeholder="Enter Title"
                                name="title" value="{{$page['title'] ?? ''}}" data-empty="Enter Title" data-max="250"
                                data-lengtherr="Maximum 250 characters allowed" />
                        </div>
                        <div class="form-group @if(config('page.hidecontent.'.$slug)) hidden @endif hidden">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content"
                                placeholder="Enter Content" data-empty="Enter Content" data-allowed="page">{{$page['content'] ?? ''}}</textarea>
                        </div>
                        <div class="form-group hidden">
                            <label for="title">Category</label>
                            <br>
                            <?php 
                            $categories = (isset($page['category']) && $page['category']!=null)?json_decode($page['category'],true):[];
                            ?>
                            @foreach($pagecategory as $nkey => $nval)
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
                                <option value="1" @if(isset($page['status']) && $page['status']=='1' ) selected
                                    @endif>Enable</option>
                                <option value="0" @if(isset($page['status']) && $page['status']=='0' ) selected
                                    @endif>Disable</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title">Publish Date</label>
                            <?php $puhdate = (isset($page['publish_on']) && $page['publish_on']!='')?date("d-m-Y", strtotime($page['publish_on'] ?? '')):'';?>
                            <input type="text" readonly class="form-control flatpicker required" value="{{$puhdate}}"
                                id="publish_on" placeholder="Enter Publish Date" name="publish_on"
                                data-empty="Enter Publish Date" />
                        </div>
                        <input type="hidden" name="slug" value="{{$slug}}" />
                    </div>
                </div>
                <hr>
                <hr class="sidebar-divider my-0">
                <div class="card customCard">

                    <div class="card-body pt-2">
                        <h1>SEO Settings</h1>
                        <div class="form-group">
                            <label for="content">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description"
                                placeholder="Enter Meta Description" data-empty="Enter Meta Description" data-min="3"
                                data-lengtherr="Minimum 3 characters required">{{$page['meta_description'] ?? ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="content">Meta Keywords</label>
                            <textarea class="form-control" id="meta_keywords" name="meta_keywords"
                                placeholder="Enter Meta Keywords" data-empty="Enter Meta Keywords" data-min="3"
                                data-lengtherr="Minimum 3 characters required">{{$page['meta_keywords'] ?? ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="content">Analytics</label>
                            <textarea class="form-control" id="analytics" name="analytics"
                                placeholder="Enter Analytics" data-empty="Enter Analytics" data-min="3"
                                data-lengtherr="Minimum 3 characters required">{{$page['analytics'] ?? ''}}</textarea>
                        </div>
                    </div>


                </div>
                @if(config('page.section.'.$slug))
                @foreach(config('page.section.'.$slug) as $k => $v)
                <div class="card customCard">

                    <div class="card-body pt-2">



                        <div class="">
                            <div class="row clearfix">
                                <div class="col-md-12 table-responsive">
                                    <table id="tab_logic{{$k}}" class="table table-bordered table-hover table-sortable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">{{$v['title']}}</th>
                                                @if(!config('page.section.'.$slug.'.'.$k.'.static'))
                                                <th class="text-center"
                                                    style="border-top: 1px solid rgb(255, 255, 255); border-right: 1px solid rgb(255, 255, 255);width:100px;">
                                                </th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if((config('page.section.'.$slug.'.'.$k.'.static') &&
                                            empty($pagesections[$k])) ||
                                            (!config('page.section.'.$slug.'.'.$k.'.static')))
                                            <tr id="{{$k}}addr0" data-id="0"
                                                class="@if(!config('page.section.'.$slug.'.'.$k.'.static')) hidden novalidation @endif ">
                                                <td data-name="stitle">
                                                    @if(isset($v['blade']))
                                                    @include('Page::admin.section.'.$v['blade'],['k'=>$k,'firstrow'=>true])
                                                    @else
                                                    @include('Page::admin.section.fields',['k'=>$k,'firstrow'=>true])
                                                    @endif
                                                </td>
                                                @if(!config('page.section.'.$slug.'.'.$k.'.static'))
                                                <td data-name="del"><button name="{{$k}}del0"
                                                        class="btn btn-danger row-remove"><i
                                                            class="far fa-trash-alt"></i></button>
                                                        <span class="moveupbtn" title="Move Up"><i class="fas fa-arrow-alt-circle-up"></i></span>
                                                        <span class="movedownbtn" title="Move Down"><i class="fas fa-arrow-alt-circle-down"></i></span>
                                                </td>
                                                @endif
                                            </tr>
                                            @endif
                                            @if(isset($pagesections[$k]))
                                            @foreach($pagesections[$k] as $sk => $sv)
                                            <tr id="{{$k}}addr{{$sk+1}}" data-id="{{$sk+1}}" class="">
                                                <td data-name="{{$k}}stitle">
                                                    @if(isset($v['blade']))
                                                    @include('Page::admin.section.'.$v['blade'],['k'=>$k,'edit' => true])
                                                    @else
                                                    @include('Page::admin.section.fields',['k'=>$k,'edit' => true])
                                                    @endif
                                                </td>
                                                @if(!config('page.section.'.$slug.'.'.$k.'.static'))
                                                <td data-name="del"><a
                                                        href="{{URL('admin/page/sectiondelete/'.$sv['id'])}}"
                                                        name="{{$k}}del{{$sk+1}}" class="btn btn-danger row-remove"><i
                                                            class="far fa-trash-alt"></i></button></a>
                                                            <span class="moveupbtn" title="Move Up"><i class="fas fa-arrow-alt-circle-up"></i></span>
                                                            <span class="movedownbtn" title="Move Down"><i class="fas fa-arrow-alt-circle-down"></i></span>
                                                        </td>
                                                @endif
                                            </tr>
                                            @endforeach
                                            @endif



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if(!config('page.section.'.$slug.'.'.$k.'.static'))
                            <a id="add_row{{$k}}" class="btn btn-primary pull-left text-white add_row"
                                data-section="{{$k}}">Add Row</a><input type="hidden" name="loopcount{{$k}}"
                                value="{{count($pagesections[$k] ?? [])}}" id="loopcount{{$k}}">
                            @endif

                        </div>
                    </div>


                </div>
                <hr>
                @endforeach
                @endif

                <button type="button" class="btn btn-success pull-right" onclick="if(validation(event,'#pageform')==true) $('#pageform').submit();">Save Draft</button><a href="{{URL()->previous()}}" class="btn btn-success">Back</a><button type="button" class="btn btn-success pull-right" id="publishbtn">Publish</button>
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
    $('body').on('click','#publishbtn',function(e){
        e.preventDefault();
        var _this = $(this);
        _this.prop('disabled', true);
        var result = $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: root + '/admin/page/update?fromdraft=1&lang='+locale,
            type: "POST",
            datatype: "json",
            data: new FormData($('#pageform')[0]),
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
@if(isset($pagesections) && count($pagesections)==0)
<script>
    $(document).ready(function(){
        $('#add_rowcontent').click();
    });
</script>
@endif
@switch($slug)
@case('home')
@include('admin.gallery.inc.home.script')
@endswitch
@endpush
@include('admin.inc.tinyscript')