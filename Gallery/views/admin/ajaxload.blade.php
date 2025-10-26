<div class="row w-100 px-3">
@foreach($gallery as $g)
<div class="col-12 col-sm-2 col-lg-2 gallerydiv">
  <div class="buttondiv">
    <?php
    $ext = pathinfo($g->path, PATHINFO_EXTENSION);
    ?>
    @if($ext=='pdf')
    <?php 
    $datatype='pdf';
        $backimage = asset('image/pdf.png');
        ?>
    <a data-toggle="modal" data-target="#galleryImageDelete{{$g->id}}" href="javascript:void(0);"
      class="badge badge-success float-right">Delete</a>
    <a href="{{asset('/gallery/'.$g->path)}}" target="_blank" class="badge badge-success float-left">View</a>
    @elseif(strtolower($ext)=='docx')
    <?php 
        $datatype='docx';
        $backimage = asset('image/document.png');
        ?>
    <a data-toggle="modal" data-target="#galleryImageDelete{{$g->id}}" href="javascript:void(0);"
      class="badge badge-success float-right">Delete</a>
    <a href="{{asset('/gallery/'.$g->path)}}" target="_blank" class="badge badge-success float-left">View</a>
    @elseif($ext=='mp4')
    <?php 
        $datatype='mp4';
        $backimage = asset('image/video.png');
        ?>
    <a data-toggle="modal" data-target="#galleryImageDelete{{$g->id}}" href="javascript:void(0);"
      class="badge badge-success float-right">Delete</a>
    <a href="javascript:void(0);" data-toggle="modal" data-target="#galleryImage{{$g->id}}"
      class="badge badge-success float-left">View</a>
    @else
    <?php
        $datatype='image';
        $backimage = asset('gallery/'.$g->path);
        ?>
    <a data-toggle="modal" data-target="#galleryImageDelete{{$g->id}}" href="javascript:void(0);"
      class="badge badge-success float-right">Delete</a>
    <a href="javascript:void(0);" data-toggle="modal" data-target="#galleryImage{{$g->id}}"
      class="badge badge-success float-left">View</a>
    @endif
  </div>
  <div class="thumbnail imgselect" data-type="{{ $datatype }}" data-image="{{$g->path}}"
    style="background:url('{{ $backimage }}');height:100px;background-position:center;background-size: 100%;margin-bottom:20px;cursor:pointer;    background-repeat: no-repeat;background-color: rgb(0, 0, 0);">
  </div>
</div>
<div id="galleryImage{{$g->id}}" class="modal fade galleryImage" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" onclick="hidegalleryImage();">&times;</button>
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
        <button type="button" class="btn btn-default" onclick="hidegalleryImage();">Close</button>
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
          <a data-href="{{URL('admin/gallery/delete/'.$g->id)}}" href="javascript:void(0);"
            class="imgdelete btn btn-sm btn-danger">Yes</a>&nbsp;&nbsp;
          <a onclick="hidegalleryImageDelete();" href="javascript:void(0);" class="btn btn-sm btn-success">No</a>
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
@if((($gallerycount-$loff) > $limit) && ($gallerycount!=($limit+12))) <button
  class="btn btn-info loadmoregallery form-control" id="loadmoregallery">Load More</button> @endif
<script>
  $('.galleryImage').on('hidden.bs.modal', function() {
    $('#galleryModal').css('overflow', 'auto');
  });
</script>