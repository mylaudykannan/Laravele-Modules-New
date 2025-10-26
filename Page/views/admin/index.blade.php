@extends('admin.layouts.app')
@section('pagetitle','Pages')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card customCard my-4">
                <!-- Card header -->
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                      <h5 class="text-white text-capitalize pl-3 mb-0">Page</h5>
                      <a href="{{URL('admin/page/add')}}" class="btn btn-default mb-0 mr-3 btn-sm text-white">ADD NEW PAGE</a>
                    </div>
                </div>

                <div class="card-body pt-2">
                    <div class="row">
                        <div class="col-12 col-md-10 col-lg-8 ml-auto">
                            <form action="{{ URL('admin/page')}}" method="get">
                                <div class="row">
                                    <div class="col-10">
                                        <input type="text" class="form-control" placeholder="Search" name="search" value="{{ $_GET['search'] ?? '' }}">
                                    </div>
                                    <div class="col-2">
                                        <input type="submit" class="btn btn-success" placeholder="Last name" value="Submit">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $pk => $p)
                                <tr>
                                    <td>{{ $pages->firstItem() + $pk }}</td>
                                    <td>{{$p->title}}</td>
                                    <td>{{date('d-m-Y',strtotime($p->created_at))}}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-default dropdown-toggle" type="button"
                                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                                Action
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a href="{{URL('admin/page/edit/'.$p->pointslug)}}"
                                                    class="dropdown-item">Edit English</a>
                                                <a href="{{URL('admin/page/edit/'.$p->pointslug)}}?lang=ar"
                                                    class="dropdown-item">Edit Arabic</a>
                                                {{-- <a href="{{URL('admin/page/edit/'.$p->pointslug)}}?lang=ru"
                                                    class="dropdown-item">Edit Russian</a>
                                                <?php $urlprefix = ($locale=='en')?'':$locale.'/';?>
                                                @if(!empty($p->draft))
                                                <a href="{{URL('admin/draftpage/edit/'.$p->pointslug)}}" class="dropdown-item">Edit Draft</a>
                                                <?php                                                 
                                                $previewurl = (config('page.url.'.$p->pointslug))?URL($urlprefix.config('page.url.'.$p->pointslug).'?preview=1'):URL($urlprefix.'page/'.$p->slug.'?preview=1');?>
                                                <a href="{{$previewurl}}" class="dropdown-item" target="_blank">Preview</a>
                                                @endif
                                                <?php $viewurl = (config('page.url.'.$p->pointslug))?URL($urlprefix.config('page.url.'.$p->pointslug)):URL($urlprefix.'page/'.$p->slug);?>
                                                <a href="{{$viewurl}}" class="dropdown-item" target="_blank">View</a> --}}
                                                <a class="dropdown-item" href="javascript:;" data-toggle="modal" data-target="#deletePageModal{{$p->id}}">Delete</a>
                                            </div>
                                        </div>

                                        <div class="modal " id="deletePageModal{{$p->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
                                            <div class="modal-dialog modal-md" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure want to delete {{$p->title}}?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <a href="{{URL('admin/page/delete/'.$p->id)}}" class="btn btn-primary">Yes</a>&nbsp;&nbsp;<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
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
                            {{$pages->appends(request()->input())->links('pagination.admin')}}
                        </div>
                        {{-- <ul class="pagination justify-content-center mb-0">
                            <li class="page-item disabled">
                                <a class="page-link mr-4" href="#" tabindex="-1">
                                    Page
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">
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