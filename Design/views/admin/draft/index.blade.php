@extends('admin.layouts.app')
@section('designtitle','Designs')
@section('content')
<div class="container-fluid">
    <div class="row contentTopMrg">
        <div class="col-12">
            <div class="card customCard">
                <!-- Card header -->
                <div class="card-header border-0">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <form action="{{ URL('admin/draftdesign')}}" method="get">
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
                                        <a href="{{URL('admin/draftdesign/add')}}" class="btn btn-primary float-right"><i
                                                class="ni ni-fat-add"></i>ADD NEW Draft</a>
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
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($designs as $p)
                            <tr>
                                <td>{{$p->title}}</td>
                                <td>{{$p->slug}}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-default dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Action
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a href="{{URL('admin/draftdesign/edit/'.$p->pointslug)}}"
                                                class="dropdown-item">Edit English</a>
                                            {{-- <?php $previewurl = (config('design.url.'.$p->pointslug))?URL(config('design.url.'.$p->pointslug).'?preview=1'):URL('design/'.$p->pointslug.'?preview=1');?>
                                            <a href="{{$previewurl}}" class="dropdown-item" target="_blank">Preview</a> --}}
                                            <a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#deleteDesignModal{{$p->id}}">Delete</a>
                                        </div>
                                    </div>

                                    <div class="modal " id="deleteDesignModal{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
                                        <div class="modal-dialog modal-md" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Are you sure want to delete {{$p->title}}?</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <a href="{{URL('admin/draftdesign/delete/'.$p->id)}}" class="btn btn-primary">Yes</a>&nbsp;&nbsp;<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
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
                            {{$designs->appends(request()->input())->links('pagination.admin')}}
                        </div>
                    </nav>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection