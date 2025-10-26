<div class="card customCard shadow-none m-0">
    <!-- Card header -->
    <?php
    $allowed = ['all', 'image', 'pdf', 'docx', 'mp4'];
    ?>
    <div class="card-header border-0 p-0">
        <div class="row">
            <div class="col-12">
                <h3 class="m-0 contentHead">
                    <form id="galleryfilterform">
                        <div class="col-sm-4 float-left">
                            <div class="">

                                <input type="text" class="form-control" placeholder="Search" name="name"
                                    autocomplete="off" />
                            </div>
                        </div>
                        <div class="col-sm-4 float-left suggestiondiv">

                            <div class="">

                                <select name="category" id="" class="form-control gallerycategory">
                                    <option value="">Category</option>
                                    @foreach ($gallerycategory as $k => $v)
                                        <option value="{{ $v->category }}"
                                            @if (isset($_GET['category']) && $_GET['category'] == $v->category) selected @endif>{{ $v->category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4 float-left">

                            <div class="">

                                <select name="type" id="" class="form-control">
                                    @if (in_array('all', $allowed))
                                        <option value="">Type</option>
                                    @endif
                                    @if (in_array('image', $allowed))
                                        <option value="image" @if (isset($_GET['type']) && $_GET['type'] == 'image') selected @endif>Image
                                        </option>
                                    @endif
                                    @if (in_array('pdf', $allowed))
                                        <option value="pdf" @if (isset($_GET['type']) && $_GET['type'] == 'pdf') selected @endif>PDF
                                        </option>
                                    @endif
                                    @if (in_array('docx', $allowed))
                                        <option value="docx" @if (isset($_GET['type']) && $_GET['type'] == 'docx') selected @endif>
                                            Document</option>
                                    @endif
                                    @if (in_array('mp4', $allowed))
                                        <option value="mp4" @if (isset($_GET['type']) && $_GET['type'] == 'mp4') selected @endif>Video
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 float-left">

                            <div class="">
                                <button type="button" class="btn btn-sm btn-primary" id="galleryfiltersubmit"
                                    onclick="refreshgallery();">Search</button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-sm btn-primary" id="galleryfilterreset"
                                    onclick="$('#galleryfilterform')[0].reset();refreshgallery();">Reset</button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-sm btn-primary" id="showgalleryupload">+ Add
                                    New</button>

                            </div>
                        </div>
                    </form>
                </h3>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="gallerymessage"></div>
        <form id="galleryform" enctype="multipart/form-data" class="hidden">
            <div class="form-group m-0">
                <div class="col-sm-4 float-left">
                    <div class="">
                        {{-- <label class="custom-file-label" id="choosefiletext" for="inputGroupFile01">Choose
                            file</label> --}}
                        <input type="file" class="form-control-file" id="inputGroupFile01"
                            aria-describedby="inputGroupFileAddon01" name="file">                      
                    </div>
                </div>
                <div class="col-sm-4 float-left">
                    <div class="">

                        <select name="category" id="" class="form-control gallerycategory">
                            <option value="">Category</option>
                            @foreach ($gallerycategory as $k => $v)
                                <option value="{{ $v->category }}" @if ($v->category == 'General') selected @endif>
                                    {{ $v->category }}</option>
                            @endforeach
                        </select>
                        <a id="addcategorybtn" href="javascript:;">+ Add Category</a>
                    </div>
                </div>
                <div class="col-sm-4 float-left">
                    <div class="">

                        <input type="text" class="form-control" id="filename" placeholder="Name" name="name"
                            autocomplete="off" maxlength="50" />
                    </div>
                </div>
                <div class="col-sm-12 float-left">
                    <div class="">
                        <button type="button" class="btn btn-primary btn-sm"
                            id="gallerysubmit">Upload</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-primary btn-sm" id="gallerysearchbtn">Search</button>
                    </div>
                </div>
            </div>
            <br>
            <br>

        </form>
        <form id="gallerycategoryform" enctype="multipart/form-data" class="hidden mb-4">
            <div class="form-group">
                <div class="col-sm-4 float-left">
                    <div class="">
                        <input type="text" class="form-control" id="category" placeholder="Category" name="category"
                            autocomplete="off" />
                    </div>
                </div>
                <div class="col-sm-8 float-left">
                    <div class="">
                        <button type="button" class="btn btn-primary btn-sm"
                            id="gallerycategorysubmit">Add</button>&nbsp;&nbsp;<button type="button"
                            class="btn btn-primary btn-sm" id="gallerycategorycancel">Close</button>
                    </div>
                </div>
            </div>
            <br>
            <br>
        </form>
        <div class="form-group m-0">
            <div class="row" id="gallery">
                @foreach ($gallery as $g)
                    <div class="col-md-2 gallerydiv">
                        <div class="buttondiv">
                            <?php
                            $ext = pathinfo($g->path, PATHINFO_EXTENSION);
                            ?>
                            @if ($ext == 'pdf')
                                <?php
                                $datatype = 'pdf';
                                $backimage = asset('image/pdf.png');
                                ?>
                                <a data-toggle="modal" data-target="#galleryImageDelete{{ $g->id }}"
                                    href="javascript:void(0);" class="badge badge-success float-right">Delete</a>
                                <a href="{{ asset('/gallery/' . $g->path) }}" target="_blank"
                                    class="badge badge-success float-left">View</a>
                            @elseif($ext == 'docx')
                                <?php
                                $datatype = 'docx';
                                $backimage = asset('image/document.png');
                                ?>
                                <a data-toggle="modal" data-target="#galleryImageDelete{{ $g->id }}"
                                    href="javascript:void(0);" class="badge badge-success float-right">Delete</a>
                                <a href="{{ asset('/gallery/' . $g->path) }}" target="_blank"
                                    class="badge badge-success float-left">View</a>
                            @elseif($ext == 'mp4')
                                <?php
                                $datatype = 'mp4';
                                $backimage = asset('image/video.png');
                                ?>
                                <a data-toggle="modal" data-target="#galleryImageDelete{{ $g->id }}"
                                    href="javascript:void(0);" class="badge badge-success float-right">Delete</a>
                                <a href="javascript:void(0);" data-toggle="modal"
                                    data-target="#galleryImage{{ $g->id }}"
                                    class="badge badge-success float-left">View</a>
                            @else
                                <?php
                                $datatype = 'image';
                                $backimage = asset('gallery/' . $g->path);
                                ?>
                                <a data-toggle="modal" data-target="#galleryImageDelete{{ $g->id }}"
                                    href="javascript:void(0);" class="badge badge-success float-right">Delete</a>
                                <a href="javascript:void(0);" data-toggle="modal"
                                    data-target="#galleryImage{{ $g->id }}"
                                    class="badge badge-success float-left">View</a>
                            @endif
                        </div>
                        <div class="thumbnail imgselect" data-type="{{ $datatype }}"
                            data-image="{{ $g->path }}"
                            style="background:url('{{ $backimage }}');height:100px;background-position:center;background-size: 100%;margin-bottom:20px;cursor:pointer;    background-repeat: no-repeat;background-color: rgb(0, 0, 0);">
                        </div>
                    </div>
                    <div id="galleryImage{{ $g->id }}" class="modal fade galleryImage" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close"
                                        onclick="hidegalleryImage();">&times;</button>
                                </div>
                                <div class="modal-body">
                                    @if ($ext == 'mp4')
                                        <video width="100%" height="240" controls>
                                            <source src="{{ asset('gallery/' . $g->path) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <img src="{{ asset('gallery/' . $g->path) }}" alt="Lights"
                                            style="width:100%">
                                    @endif
                                    <div class="alert alert-info"><strong>URL :
                                        </strong>{{ asset('gallery/' . $g->path) }}
                                        @if ($g->category != '')
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
                    <div id="galleryImageDelete{{ $g->id }}" class="modal fade galleryImageDelete"
                        role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-body">
                                    <h1 class="text-center">Are you sure want to delete?</h1>
                                    <div class="text-center">
                                        <a data-href="{{ URL('admin/gallery/delete/' . $g->id) }}"
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
                <input type="hidden" id="gallerylimit" value="{{ $limit }}">
                <?php 
                $loff = $gallerycount % 12;
                ?>
                @if ($gallerycount - $loff >= $limit && $gallerycount != $limit && $gallerycount > 12)
                    <button class="btn btn-info loadmoregallery form-control" id="loadmoregallery">Load More</button>
                @endif
                <script>
                    $('.galleryImage').on('hidden.bs.modal', function() {
                        $('#galleryModal').css('overflow', 'auto');
                    });
                </script>
            </div>
        </div>
    </div> 
</div>
