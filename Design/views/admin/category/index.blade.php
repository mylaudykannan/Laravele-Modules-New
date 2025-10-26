@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card customCard my-4">
                <!-- Card header -->
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                      <h5 class="text-white text-capitalize pl-3 mb-0">Design Category</h5>
                      <a href="{{URL('admin/designcategory/add')}}" class="btn btn-default mb-0 mr-3 btn-sm text-white">Add Category</a>
                    </div>
                </div>

                <div class="card-body pt-2">
                    <div class="table-responsive p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($design as $p)
                                <tr>
                                    <td>{{$p->title}}</td>
                                    <td>{{$p->status}}</td>
                                    <td><a href="{{URL('admin/designcategory/edit/'.$p->id)}}"
                                            class="btn btn-sm btn-primary">Edit</a>&nbsp;&nbsp;<a href="javascript::void();"
                                            class="btn btn-sm btn-primary" data-toggle="modal"
                                            data-target="#deletedesigncategorymodal{{$p->id}}">Delete</a>

                                        <div class="modal" id="deletedesigncategorymodal{{$p->id}}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body text-center">
                                                        <h1>Are you sure want to delete?</h1>
                                                        <a href="{{URL('admin/designcategory/delete/'.$p->id)}}"
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
                            {{$design->appends(request()->input())->links('pagination.admin')}}
                        </div>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection