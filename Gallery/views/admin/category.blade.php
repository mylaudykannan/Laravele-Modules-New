@extends('admin.layouts.app')
@push('stylesheets')
@endpush
@section('content')
<!-- Page content -->
<div class="container-fluid">
    @include('Gallery::admin.inc.alert')
    <div class="row contentTopMrg">
        <div class="col-12">
            <div class="card customCard">
                <!-- Card header -->
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <form action="{{ URL('admin/gallery/category')}}" method="get">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Search" name="search"
                                            value="{{ $_GET['search'] ?? '' }}">
                                    </div>
                                    <div class="col">
                                        <input type="submit" class="btn btn-success" placeholder="Last name"
                                            value="submit">
                                    </div>
                                    <div class="col">
                                        <a href="{{URL('admin/gallery/category/add')}}" class="btn btn-primary float-right"><i
                                                class="ni ni-fat-add"></i>ADD NEW CATEGORY</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body pt-2">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gallerycategory as $k => $v)
                            <tr>
                                <td>{{$v->category}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-default dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{URL('admin/page/caregory/edit/'.$v->id)}}"
                                                class="dropdown-item">Edit</a>
                                            <a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#deleteCategoryModal{{$v->id}}">Delete</a>
                                                
                                        </div>
                                    </div>
                                    <div class="modal " id="deleteCategoryModal{{$v->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Are you sure want to delete {{$v->category}}?</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <a href="{{URL('admin/gallery/category/delete/'.$v->id)}}" class="btn btn-primary">Yes</a>&nbsp;&nbsp;<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
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
                <!-- Card footer -->
                <div class="card-footer py-4">
                    <nav aria-label="...">
                        <div class="justify-content-center mb-0">
                            {{$gallerycategory->appends(request()->input())->links('pagination.admin')}}
                        </div>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script></script>
@endpush