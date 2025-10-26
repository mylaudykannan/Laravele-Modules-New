@extends('admin.layouts.app')
@push('stylesheets')
<style>
    .thumbnail {
        margin-bottom: 15px;
    }

    .suggestionresult {
        position: absolute;
        background: #fff;
        width: 100%;
        z-index: 99999999;
        top: 37px;
    }

    .suggestionresult li {
        cursor: pointer;
    }

    .suggestionclose {
        position: absolute;
        right: 6px;
        top: 0px;
        z-index: 9;
        width: fit-content;
        cursor: pointer;
    }
</style>
@endpush
@section('content')
<!-- Page content -->
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card customCard my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                      <h5 class="text-white text-capitalize pl-3 mb-0">Gallery</h5>
                      <button type="button" class="btn btn-default mb-0 mr-3 btn-sm text-white"
                                        id="showgalleryupload">+ Add New</button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="galleryfilterform">
                        <div class="row">
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Search" name="name" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3 suggestiondiv">
                                <div class="form-group">
                                    <select name="category" id="" class="form-control gallerycategory">
                                        <option value="">Category</option>
                                        @foreach($gallerycategory as $k => $v)
                                        <option value="{{$v->category}}">{{$v->category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="form-group">
                                    <select name="type" id="" class="form-control">
                                        <option value="">Type</option>
                                        <option value="image">Image</option>
                                        <option value="pdf">PDF</option>
                                        <option value="docx">Document</option>
                                        <option value="mp4">Video</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="d-flex">
                                    <button type="button" class="btn btn-primary"
                                        id="galleryfiltersubmit"
                                        onclick="refreshgallery();">Search</button>
                                    <button type="button" class="btn btn-primary ml-2" id="galleryfilterreset" onclick="$('#galleryfilterform')[0].reset();refreshgallery();">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="gallerymessage"></div>
                    <form id="galleryform" enctype="multipart/form-data" class="hidden">
                        <div class="row">
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="form-group customFileUpload">
                                    <input type="file" class="custom-file-input form-control" id="inputGroupFile01"
                                        aria-describedby="inputGroupFileAddon01" name="file">
                                    <label class="custom-file-label" id="choosefiletext" for="inputGroupFile01">Choose
                                        file</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="form-group">
                                    <select name="category" id="" class="form-control gallerycategory">
                                        <option value="">Category</option>
                                        @foreach($gallerycategory as $k => $v)
                                        <option value="{{$v->category}}">{{$v->category}}</option>
                                        @endforeach
                                    </select>
                                    <a href="javascript:void(0)"><span id="addcategorybtn">+ Add Category</span></a>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="form-group">

                                    <input type="text" class="form-control" id="filename" placeholder="Name" name="name"
                                        autocomplete="off" maxlength="50"/>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-xl-3">
                                <div class="custom-file">
                                    <button type="button" class="btn btn-primary"
                                        id="gallerysubmit">Upload</button>
                                    &nbsp;&nbsp;
                                    <button type="button" class="btn btn-primary"
                                        id="gallerysearchbtn">Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="gallerycategoryform" enctype="multipart/form-data" class="hidden mb-4">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="category" placeholder="Category"
                                        name="category" autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-5">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary"
                                        id="gallerycategorysubmit">Add</button>&nbsp;&nbsp;<button type="button"
                                        class="btn btn-primary" id="gallerycategorycancel">Close</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="">
                        <div class="col-12" id="gallery">
                            <div class="row w-100 px-3">
                                @foreach($gallery as $g)
                                <div class="col-12 col-sm-2 col-lg-2 gallerydiv">
                                    <div class="buttondiv">
                                        <?php
                                        $ext = pathinfo($g->path, PATHINFO_EXTENSION);
                                        ?>
                                        @if($ext=='pdf')
                                        <?php 
                                            $backimage = asset('image/pdf.png');
                                            ?>
                                        <a data-toggle="modal" data-target="#galleryImageDelete{{$g->id}}"
                                            href="javascript:void(0);" class="badge badge-success float-right">Delete</a>
                                        <a href="{{asset('/gallery/'.$g->path)}}" target="_blank"
                                            class="badge badge-success float-left">View</a>
                                        @elseif($ext=='docx')
                                        <?php 
                                            $backimage = asset('image/document.png');
                                            ?>
                                        <a data-toggle="modal" data-target="#galleryImageDelete{{$g->id}}"
                                            href="javascript:void(0);" class="badge badge-success float-right">Delete</a>
                                        <a href="{{asset('/gallery/'.$g->path)}}" target="_blank"
                                            class="badge badge-success float-left">View</a>
                                        @elseif($ext=='mp4')
                                        <?php 
                                            $backimage = asset('image/video.png');
                                            ?>
                                        <a data-toggle="modal" data-target="#galleryImageDelete{{$g->id}}"
                                            href="javascript:void(0);" class="badge badge-success float-right">Delete</a>
                                        <a href="javascript:void(0);" data-toggle="modal"
                                            data-target="#galleryImage{{$g->id}}"
                                            class="badge badge-success float-left">View</a>
                                        @else
                                        <?php
                                            $backimage = asset('gallery/'.$g->path);
                                            ?>
                                        <a data-toggle="modal" data-target="#galleryImageDelete{{$g->id}}"
                                            href="javascript:void(0);" class="badge badge-success float-right">Delete</a>
                                        <a href="javascript:void(0);" data-toggle="modal"
                                            data-target="#galleryImage{{$g->id}}"
                                            class="badge badge-success float-left">View</a>
                                        @endif
                                    </div>
                                    <div class="thumbnail imgselect" data-image="{{$g->path}}"
                                        style="background:url('{{ $backimage }}');height:100px;background-position:center;background-size: 100%;margin-bottom:20px;cursor:pointer;    background-repeat: no-repeat;background-color: rgb(0, 0, 0);">
                                    </div>
                                </div>
                                <div id="galleryImage{{$g->id}}" class="modal fade galleryImage" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                    onclick="hidegalleryImage();">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                @if($ext=='mp4')
                                                <video width="100%" height="240" controls>
                                                    <source src="{{ asset('gallery/'.$g->path) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                                @else
                                                <img src="{{ asset('gallery/'.$g->path) }}" alt="Lights" style="width:100%">
                                                @endif
                                                <div class="alert alert-info"><strong>URL :
                                                    </strong>{{ asset('gallery/'.$g->path) }}
                                                    @if($g->category!='')
                                                    <br>
                                                    <strong>Catgory :
                                                    </strong>{{ $g->category }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default"
                                                    onclick="hidegalleryImage();">Close</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div id="galleryImageDelete{{$g->id}}" class="modal fade galleryImageDelete" role="dialog">
                                    <div class="modal-dialog">

                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <h1 class="text-center">Are you sure want to delete?</h1>
                                                <div class="text-center">
                                                    <a data-href="{{URL('admin/gallery/delete/'.$g->id)}}"
                                                        href="javascript:void(0);"
                                                        class="imgdelete btn btn-sm btn-danger">Yes</a>&nbsp;&nbsp;
                                                    <a onclick="hidegalleryImageDelete();" href="javascript:void(0);"
                                                        class="btn btn-sm btn-success">No</a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <input type="hidden" id="gallerylimit" value="{{$limit}}">
                            <?php
                            $loff = $gallerycount%12;
                            ?>
                            @if((($gallerycount-$loff) >= $limit) && ($gallerycount!=$limit) && $gallerycount>12) <button
                                class="btn btn-info loadmoregallery form-control" id="loadmoregallery">Load More</button> @endif
                            <script>
                                $('.galleryImage').on('hidden.bs.modal', function() {
                                    $('#galleryModal').css('overflow', 'auto');
                                });
                            </script>
                        </div>
                    </div>
                </div> 
                <!-- Card footer -->
                <div class="card-footer py-4">

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
@push('scripts')
<script src="{{ asset('vendor/gallery/js/gallery.js') }}"></script>
@endpush