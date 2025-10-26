<div id="designcontent">
 
    <div id="designaccordion">

        <div class="card">


            <div class="row">
                <div class="col-12 col-md-10 col-lg-8 ml-auto">
                    <form method="get" id="designsearch">
                        <div class="row">
                            <div class="col-5">
                                <input type="text" class="form-control" placeholder="Search" name="search" value="{{$request->search ?? ''}}">
                            </div>
                            <div class="col-5">
                                <select class="form-control" name="category">
                                    <option value="">Category</option>
                                    @foreach($designcategory as $tv)
                                    <option value="{{$tv['title']}}" @if(isset($request->category) && $request->category==$tv['title']) selected @endif>{{$tv['title']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <input type="submit" class="btn btn-success submitbtn" placeholder="Last name" value="Submit">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @foreach ($design as $dsk => $dsv)
                <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#designcollapse{{ $dsk }}">
                        {{ $dsv['title'] }}<span class="btn btn-sm btn-primary ml-2">View Design</span>
                    </a><span class="designselect btn btn-sm btn-primary ml-1">Select Design</span>
                </div>
                <div id="designcollapse{{ $dsk }}" class="collapse designcard" data-parent="#designaccordion">
                    <div class="card-body">
                        <div class="col-12 p-0 designblock">
                            {!!$dsv['content']!!}
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="row">
                <div class="col-12 d-flex justify-content-center mt-5 ajaxpagination">
                    {{$design->appends(request()->input())->links('pagination.ajax')}}
                </div>
            </div>
        </div>
    </div>
    

</div>
