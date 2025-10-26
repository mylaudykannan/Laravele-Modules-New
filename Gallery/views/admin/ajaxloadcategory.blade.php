<option value="">Category</option>
@foreach($gallerycategory as $k => $v)
<option value="{{$v->category}}">{{$v->category}}</option>
@endforeach