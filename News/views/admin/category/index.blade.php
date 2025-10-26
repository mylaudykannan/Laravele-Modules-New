@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card customCard my-4">
                <!-- Card header -->
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                      <h5 class="text-white text-capitalize pl-3 mb-0">News Category</h5>
                      <a href="{{URL('admin/newscategory/add')}}" class="btn btn-default mb-0 mr-3 btn-sm text-white">Add Category</a>
                    </div>
                </div>

                <div class="card-body pt-2">
                    <div class="table-responsive p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    {{-- <th>Order</th> --}}
                                    <th>Status</th>
                                    {{-- <th>Media</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($news as $p)
                                <tr>
                                    <td>{{$p->title}}</td>
                                    {{-- <td>{{$p->order}}</td> --}}
                                    <td>{{$p->status}}</td>
                                    {{-- <td>@if (strpos($p->image, '.mp4') !== false)
                                        <video width="100" height="100" controls>
                                            <source src="{{ asset('gallery/'.$p->image) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                    </video>
                                    @else
                                    <div class="thumbnail"
                                        style="background:url('{{ asset('gallery/'.$p->image) }}');height:100px;width:100px;background-position:center;background-size: 100%;margin-bottom:20px;cursor:pointer;    background-repeat: no-repeat;background-color: rgb(0, 0, 0);">
                                    </div>
                                    @endif
                                    </td> --}}
                                    <td><a href="{{URL('admin/newscategory/edit/'.$p->id)}}"
                                            class="btn btn-sm btn-primary">Edit</a>&nbsp;&nbsp;<a href="javascript::void();"
                                            class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#deletenewscategorymodal{{$p->id}}">Delete</a>

                                        <div class="modal" id="deletenewscategorymodal{{$p->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body text-center">
                                                        <h1>Are you sure want to delete?</h1>
                                                        <a href="{{URL('admin/newscategory/delete/'.$p->id)}}"
                                                            class="btn btn-sm btn-primary">Yes</a>&nbsp;&nbsp;<button
                                                            class="btn btn-danger btn-sm" data-dismiss="modal">No</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <div class="justify-content-center mb-0">
                            {{$news->appends(request()->input())->links('pagination.admin')}}
                        </div>
                        {{-- <ul class="pagination justify-content-center mb-0">
                            <li class="news-item disabled">
                                <a class="news-link mr-4" href="#" tabindex="-1">
                                    News
                                </a>
                            </li>
                            <li class="news-item active">
                                <a class="news-link" href="#">1</a>
                            </li>
                            <li class="news-item">
                                <a class="news-link" href="#">2 <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="news-item"><a class="news-link" href="#">3</a></li>
                            <li class="news-item">
                                <a class="news-link" href="#">
                                    Next
                                </a>
                            </li>
                        </ul> --}}
                    </nav>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection