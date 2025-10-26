@extends('admin.layouts.app')
@push('stylesheets')
<link rel="stylesheet" type="text/css" href="{{ asset('css/gallery.css') }}">
@endpush

@section('content')

<div class="container-fluid">
    @include('Gallery::admin.inc.alert')
    <div class="row contentTopMrg">

        <div class="col-12">
            <form action="{{ URL('admin/draftpage/store') }}" method="post" id="pageform">
                @csrf
                <div class="card customCard my-4">
                    <!-- Card header -->
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                          <h5 class="text-white text-capitalize pl-3 mb-0">Add Draft Page</h5>
                        </div>
                    </div>

                    <div class="card-body pt-2">

                        <div class="form-group">
                            @include('Gallery::admin.inc.mediainput',['name'=>'image','class' => 'required','attr' => 'data-empty=Select&nbsp;Image','sizemessage'=>'600 x 400','showalt'=>true])
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control required" id="title" placeholder="Enter Title"
                                name="title" data-empty="Enter Title" data-max="250"
                                data-lengtherr="Maximum 250 characters allowed" />
                        </div>
                        <div class="form-group hidden">
                            <label for="content">Content</label>
                            <textarea class="form-control" id="content" name="content"
                                placeholder="Enter Content" data-empty="Enter Content" data-allowed="page"></textarea>
                        </div>
                        <div class="form-group hidden">
                            <label for="title">Category</label>
                            <br>
                            @foreach($pagecategory as $nkey => $nval)
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
                <div class="card customCard">

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
                @if(!empty($page) && config('page.section.'.$page['pointslug']))
                @foreach(config('page.section.'.$page['pointslug']) as $k => $v)
                <div class="card customCard transparent">

                    <div class="card-body pt-2">



                        <div class="">
                            <div class="row clearfix">
                                <div class="col-md-12 table-responsive">
                                    <table id="tab_logic{{$k}}" class="table table-bordered table-hover table-sortable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">{{$v['title']}}</th>
                                                @if(!config('page.section.'.$page['pointslug'].'.'.$k.'.static'))
                                                <th class="text-center"
                                                    style="border-top: 1px solid rgb(255, 255, 255); border-right: 1px solid rgb(255, 255, 255);width:100px;">
                                                </th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="{{$k}}addr0" data-id="0"
                                                class="@if(!config('page.section.'.$page['pointslug'].'.'.$k.'.static')) hidden novalidation @endif ">
                                                <td data-name="stitle">
                                                    @if(isset($v['blade']))
                                                    @include('Page::admin.section.'.$v['blade'],['k'=>$k,'firstrow'=>true])
                                                    @else
                                                    @include('Page::admin.section.fields',['k'=>$k,'firstrow'=>true])
                                                    @endif
                                                </td>
                                                @if(!config('page.section.'.$page['pointslug'].'.'.$k.'.static'))
                                                <td data-name="del"><button name="{{$k}}del0"
                                                        class="btn btn-danger row-remove"><i
                                                            class="far fa-trash-alt"></i></button>
                                                        <span class="moveupbtn" title="Move Up"><i class="fas fa-arrow-alt-circle-up"></i></span>
                                                        <span class="movedownbtn" title="Move Down"><i class="fas fa-arrow-alt-circle-down"></i></span>
                                                </td>
                                                @endif
                                            </tr>



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if(!config('page.section.'.$page['pointslug'].'.'.$k.'.static'))
                            <a id="add_row{{$k}}" class="btn btn-primary pull-left text-white add_row"
                                data-section="{{$k}}">Add Row</a><input type="hidden" name="loopcount{{$k}}"
                                value="0" id="loopcount{{$k}}">
                            @endif

                        </div>
                    </div>


                </div>
                <hr>
                @endforeach
                @endif
                <button type="submit" class="btn btn-success pull-right"
                    onclick="if(validation(event,'#pageform')==true) $('#pageform').submit();">Save Draft</button> <a
                    href="{{URL()->previous()}}" class="btn btn-success">Back</a> 
            </form>
        </div>

    </div>
</div>
@include('Gallery::admin.inc.gallerymodal')
@endsection
@push('scripts')
@include('admin.inc.addsectionscript')
@include('admin.inc.tinyscript')
<script>
    var root = $('#rootfolder').val();
    $(document).ready(function(){
        $('#add_rowcontent').click();
    });
</script>
@include('Gallery::admin.inc.mediascript',['name'=>'image'])
@endpush