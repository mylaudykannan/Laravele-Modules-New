@if(isset($edit))
<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control required"
        id="{{$k}}stitle{{$sk+1}}" placeholder="Enter Title"
        name="{{$k}}stitle{{$sk+1}}" data-empty="Enter Title"
        value="{{$sv['title']}}" />
</div>
<div class="form-group">
    <label for="content">Content</label>
    <textarea class="form-control required tinyarea"
        id="{{$k}}scontent{{$sk+1}}" name="{{$k}}scontent{{$sk+1}}"
        placeholder="Enter Content"
        data-empty="Enter Content">{{$sv['content']}}</textarea>
</div>
<div class="form-group">
    <label for="title">Order</label>
    <input type="number" class="form-control required"
        id="{{$k}}sorder{{$sk+1}}" placeholder="Enter Order"
        name="{{$k}}sorder{{$sk+1}}" data-empty="Enter Order"
        value="{{$sv['order']}}" />
</div>
@else
<div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control required" id="{{$k}}stitle"
        placeholder="Enter Title" name="{{$k}}stitle"
        data-empty="Enter Title" />
</div>
<div class="form-group">
    <label for="content">Content</label>
    <textarea class="form-control required" id="{{$k}}scontent"
        name="{{$k}}scontent" placeholder="Enter Content"
        data-empty="Enter Content"></textarea>
</div>
<div class="form-group">
    <label for="title">Order</label>
    <input type="number" class="form-control required" id="{{$k}}sorder"
        placeholder="Enter Order" name="{{$k}}sorder"
        data-empty="Enter Order" />
</div>
@endif