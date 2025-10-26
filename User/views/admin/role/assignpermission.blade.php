@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
          <div class="col-12">
            <div class="card customCard my-4">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                  <h5 class="text-white text-capitalize pl-3 mb-0">{{$role['name']}} Permissions</h5>
                  <a href="{{URL('admin/role')}}" class="btn btn-default mb-0 mr-3 btn-sm text-white">Back to Roles</a>
                </div>
              </div>
              <div class="card-body px-0 pb-2">
                @foreach($role->getPermissionNames() as $k => $v)
                    <span class="text-xs">{{$v}} 
                        <a href="{{URL('admin/role/assignpermissionrevoke/'.$role['name'].'/'.$v)}}" class="btn btn-sm btn-warning ml-2">Revoke Permission</a>
                    </span>
                @endforeach
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">#</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Permission</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $k => $v)
                        <tr>
                            <td class="text-xs font-weight-bold px-3">{{ $permissions->firstItem() + $k }}</td>
                            <td class="text-xs font-weight-bold">{{$v['name']}}</td>
                            <td class="text-xs font-weight-bold">
                                @if($role->hasPermissionTo($v['name']))
                                    <a href="{{URL('admin/role/assignpermissionrevoke/'.$role['name'].'/'.$v['name'])}}" class="btn btn-sm btn-warning mb-0 font-weight-light">Revoke Permission</a>
                                @else
                                    <a href="{{URL('admin/role/assignpermissionadd/'.$role['name'].'/'.$v['name'])}}" class="btn btn-sm btn-success mb-0 font-weight-light">Assign Permission</a>
                                @endif
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