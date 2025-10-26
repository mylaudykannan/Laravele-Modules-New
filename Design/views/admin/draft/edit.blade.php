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
@endpush
@section('content')
<div class="container-fluid">
    @include('Gallery::admin.inc.alert')
    <div class="row contentTopMrg">
        <div class="col-12">
            <form action="{{ URL('admin/draftdesign/update?lang='.app()->getLocale()) }}" method="post" id="designform">
                @csrf
                <div class="card customCard">

                    <div class="card-body pt-2">
                        <?php $previewurl = (config('design.url.'.$slug))?URL(config('design.url.'.$slug).'?preview=1'):URL('design/'.$slug.'?preview=1');?>
                        <a href="{{$previewurl}}" class="btn btn-sm btn-info float-right" target="_blank">Preview</a>

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
                            <textarea class="form-control tinyarea" id="content" name="content"
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
                        <div class="form-group">
                            <label for="title">Publish Date</label>
                            <?php $puhdate = (isset($design['publish_on']) && $design['publish_on']!='')?date("d-m-Y", strtotime($design['publish_on'] ?? '')):'';?>
                            <input type="text" readonly class="form-control flatpicker required" value="{{$puhdate}}"
                                id="publish_on" placeholder="Enter Publish Date" name="publish_on"
                                data-empty="Enter Publish Date" />
                        </div>
                        <input type="hidden" name="slug" value="{{$slug}}" />
                    </div>
                </div>
                <hr>
                <hr class="sidebar-divider my-0">
                <div class="card customCard hidden">

                    <div class="card-body pt-2">
                        <h1>SEO Settings</h1>
                        <div class="form-group">
                            <label for="content">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description"
                                placeholder="Enter Meta Description" data-empty="Enter Meta Description" data-min="3"
                                data-lengtherr="Minimum 3 characters required">{{$design['meta_description'] ?? ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="content">Meta Keywords</label>
                            <textarea class="form-control" id="meta_keywords" name="meta_keywords"
                                placeholder="Enter Meta Keywords" data-empty="Enter Meta Keywords" data-min="3"
                                data-lengtherr="Minimum 3 characters required">{{$design['meta_keywords'] ?? ''}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="content">Analytics</label>
                            <textarea class="form-control" id="analytics" name="analytics"
                                placeholder="Enter Analytics" data-empty="Enter Analytics" data-min="3"
                                data-lengtherr="Minimum 3 characters required">{{$design['analytics'] ?? ''}}</textarea>
                        </div>
                    </div>


                </div>
                @if(!empty($design) && config('design.section.'.$design['pointslug']))
                @foreach(config('design.section.'.$design['pointslug']) as $k => $v)
                <div class="card customCard">

                    <div class="card-body pt-2">



                        <div class="">
                            <div class="row clearfix">
                                <div class="col-md-12 table-responsive">
                                    <table id="tab_logic{{$k}}" class="table table-bordered table-hover table-sortable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">{{$v['title']}}</th>
                                                @if(!config('design.section.'.$design['pointslug'].'.'.$k.'.static'))
                                                <th class="text-center"
                                                    style="border-top: 1px solid rgb(255, 255, 255); border-right: 1px solid rgb(255, 255, 255);width:100px;">
                                                </th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if((config('design.section.'.$design['pointslug'].'.'.$k.'.static') &&
                                            empty($designsections[$k])) ||
                                            (!config('design.section.'.$design['pointslug'].'.'.$k.'.static')))
                                            <tr id="{{$k}}addr0" data-id="0"
                                                class="@if(!config('design.section.'.$design['pointslug'].'.'.$k.'.static')) hidden novalidation @endif ">
                                                <td data-name="stitle">
                                                    @include('Design::admin.section.'.$v['blade'],['k'=>$k,'firstrow'=>true])
                                                </td>
                                                @if(!config('design.section.'.$design['pointslug'].'.'.$k.'.static'))
                                                <td data-name="del"><button name="{{$k}}del0"
                                                        class="btn btn-danger row-remove"><i
                                                            class="far fa-trash-alt"></i></button>
                                                        <input type="number" class="orderinput col-sm-2 p-0 text-center" value="0"/>
                                                </td>
                                                @endif
                                            </tr>
                                            @endif
                                            @foreach($designsections[$k] as $sk => $sv)
                                            <tr id="{{$k}}addr{{$sk+1}}" data-id="{{$sk+1}}" class="">
                                                <td data-name="{{$k}}stitle">
                                                    @include('Design::admin.section.'.$v['blade'],['k'=>$k,'edit' => true])
                                                </td>
                                                @if(!config('design.section.'.$design['pointslug'].'.'.$k.'.static'))
                                                <td data-name="del"><a
                                                        href="{{URL('admin/draftdesign/sectiondelete/'.$sv['id'])}}"
                                                        name="{{$k}}del{{$sk+1}}" class="btn btn-danger row-remove"><i
                                                            class="far fa-trash-alt"></i></button></a>
                                                            <input type="number" class="orderinput col-sm-2 p-0 text-center" value="{{$sk+1}}"/>
                                                        </td>
                                                @endif
                                            </tr>
                                            @endforeach



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if(!config('design.section.'.$design['pointslug'].'.'.$k.'.static'))
                            <a id="add_row{{$k}}" class="btn btn-primary pull-left text-white add_row"
                                data-section="{{$k}}">Add Row</a><input type="hidden" name="loopcount{{$k}}"
                                value="{{count($designsections[$k])}}" id="loopcount{{$k}}">
                            @endif

                        </div>
                    </div>


                </div>
                <hr>
                @endforeach
                @endif

                <button type="button" class="btn btn-success pull-right" onclick="if(validation(event,'#designform')==true) $('#designform').submit();">Save Draft</button><a href="{{URL()->previous()}}" class="btn btn-success">Back</a><button type="button" class="btn btn-success pull-right" id="publishbtn">Publish</button>
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
            url: root + '/admin/design/update?fromdraft=1&lang='+locale,
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
@endpush
@include('admin.inc.tinyscript')