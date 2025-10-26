@extends('admin.layouts.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card customCard my-4">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                  <h5 class="text-white text-capitalize pl-3 mb-0">Permissions</h5>
                  <a href="{{URL('admin/permission/create')}}" class="btn btn-default mb-0 mr-3 btn-sm text-white">Create</a>
                </div>
              </div>
              <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">#</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $k => $v)
                        <tr>
                            <td class="text-xs font-weight-bold px-3">{{ $permissions->firstItem() + $k }}</td>
                            <td class="text-xs font-weight-bold">{{$v['name']}}</td>
                            <td class="text-xs font-weight-bold">
                                <a href="{{URL('admin/permission/assignrole/'.$v['name'])}}" class="btn btn-sm btn-success mb-0 font-weight-light">Roles</a>
                                <a href="{{URL('admin/permission/delete/'.$v['id'])}}" class="btn btn-sm btn-danger mb-0 font-weight-light ml-2">Delete</a>
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
                        {{$permissions->appends(request()->input())->links('pagination.admin')}}
                    </div>
                </nav>
              </div>
            </div>
          </div>
        </div>
    </div>
@endsection