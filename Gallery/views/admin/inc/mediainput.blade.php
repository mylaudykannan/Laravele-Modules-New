<?php
$label = (isset($label))?$label:'Media';
$class = (isset($class))?$class:'';
$attr = (isset($attr))?$attr:'';
$hidepreview = (isset($hidepreview))?'previewhidden': '';
$showremove = (isset($showremove))?'':'hidden';
$sizemessage = (isset($sizemessage) && $sizemessage!='')?$sizemessage:'';
?>
@if(isset($edit))
<?php $mediavalue = $value;?>
@if(isset($showalt))
<?php
$mediavaluear = explode('|', $mediavalue);
$mediavalue = $mediavaluear[0];
$mediaalt = (isset($mediavaluear[1]))?$mediavaluear[1]:'';
?>
@endif
<label for="title">{{$label}} @if($sizemessage!='')<span>( Size - {{$sizemessage}} )</span>@endif</label>
<figure class="imageselect" style="">
    @if($value=='')
    <img src="{{ asset('image/placeholder.jpg') }}" class="img img-responsive bg-dark {{$hidepreview}}"
        width="100px;" />
    @else
    @if (strpos($value, '.pdf') !== false)
    <a href="{{ asset('gallery/'.$mediavalue) }}" target="_blank"><img src="{{ asset('image/pdf.png') }}"
            class="img img-responsive bg-dark {{$hidepreview}} @if (strpos($value, '.mp4') !== false) hidden @endif"
            width="100px;" /></a>
    @elseif (strpos(strtolower($value), '.docx') !== false)
    <a href="{{ asset('gallery/'.$mediavalue) }}" target="_blank"><img src="{{ asset('image/document.png') }}"
            class="img img-responsive bg-dark {{$hidepreview}} @if (strpos($value, '.mp4') !== false) hidden @endif"
            width="100px;" /></a>
    @else
    <img src="{{ asset('gallery/'.$mediavalue) }}"
        class="img img-responsive bg-dark {{$hidepreview}} @if (strpos($value, '.mp4') !== false) hidden @endif"
        width="100px;" />
    @endif
    @endif
    <video width="100" height="100" controls
        class="{{$hidepreview}} @if (strpos($value, '.mp4') == false) hidden @endif">
        <source src="{{ asset('gallery/'.$mediavalue) }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <br>
    <span class="d-flex">
        <input type="hidden" class="form-control {{$class}} galleryinputfield" id="{{$name}}" name="{{$name}}"
            value="{{$value}}" {{$attr}} />
        <button type="button" class="imgbtn btn btn-primary btn-sm">Select {{$label}}</button>
        <button type="button" class="btn btn-danger btn-sm removeimagebtn {{$showremove}}">Remove {{$label}}</button>
        @if(isset($showalt))
        <button type="button" class="btn btn-danger btn-sm altimagebtn ml-1">Alt Text</button>
        <div class="col-sm-2 p-0 ml-1 @if($mediaalt=='')hidden @endif altimageinputdiv"><input type="text" class="form-control altimageinput" placeholder="Image Alt Text" value="{{$mediaalt}}" maxlength="50"/></div>
        @endif
    </span>
</figure>
@else
<label for="title">{{$label}} @if($sizemessage!='')<span>( Size - {{$sizemessage}} )</span>@endif</label>
<figure class="imageselect" style="">
    <img src="{{ asset('image/placeholder.jpg') }}" class="img img-responsive {{$hidepreview}}" width="100px;" />
    <video width="100" height="100" controls class="hidden {{$hidepreview}}">
        Your browser does not support the video tag.
    </video>
    <br>
    <span class="d-flex">
        <input type="hidden" class="form-control {{$class}} galleryinputfield" id="{{$name}}" name="{{$name}}" {{$attr}} />
        <button type="button" class="imgbtn btn btn-primary btn-sm">Select {{$label}}</button>
        <button type="button" class="btn btn-danger btn-sm removeimagebtn {{$showremove}}">Remove {{$label}}</button>
        @if(isset($showalt))
        <button type="button" class="btn btn-danger btn-sm altimagebtn ml-1">Alt Text</button>
        <div class="col-sm-2 p-0 ml-1 hidden altimageinputdiv"><input type="text" class="form-control altimageinput" placeholder="Image Alt Text"/></div>
        @endif
    </span>    
</figure>
@endif