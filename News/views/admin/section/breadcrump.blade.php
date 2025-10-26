<?php 
$sknew = (isset($sk))?(int)$sk+1:'';
if(isset($firstrow))
{
    $sknew = '';
}
?>
@if(isset($edit))
<?php 
$scontent = json_decode($sv['content'],true);
?>
<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control required" id="{{$k}}title{{$sknew}}" placeholder="Enter Title"
        name="{{$k}}title{{$sknew}}" value="{{$scontent['title'] ?? ''}}" data-empty="Enter Title" data-max="250"
        data-lengtherr="Maximum 250 characters only allowed" />
</div>
<div class="form-group">
    <label for="title">Link</label>
    <input type="text" class="form-control required" id="{{$k}}link{{$sknew}}" placeholder="Enter Link"
        name="{{$k}}link{{$sknew}}" value="{{$scontent['link'] ?? ''}}" data-empty="Enter Link"
        data-regx="[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)"
        data-err="Enter valid URL" data-max="250" data-lengtherr="Maximum 250 characters only allowed" />
</div>
@else
<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control required" id="{{$k}}title{{$sknew}}" placeholder="Enter Title"
        name="{{$k}}title{{$sknew}}" data-empty="Enter Title" data-max="250"
        data-lengtherr="Maximum 250 characters only allowed" />
</div>
<div class="form-group">
    <label for="title">Link</label>
    <input type="text" class="form-control required" id="{{$k}}link{{$sknew}}" placeholder="Enter Link"
        name="{{$k}}link{{$sknew}}" data-empty="Enter Link"
        data-regx="[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)"
        data-err="Enter valid URL" data-max="250" data-lengtherr="Maximum 250 characters only allowed" />
</div>
@endif