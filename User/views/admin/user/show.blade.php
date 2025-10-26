@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xl-9">
                <div class="card customCard mb-4">
                  <div class="card-header p-0">
                    <div class="pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="mb-3">
                                <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                                    <li class="breadcrumb-item"><a href="javascript:;">Application</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">{{$user['name']}}</a></li>
                                </ol>
                            </div>
                            <h5 class="text-capitalize pl-3 mb-0">{{$user['name']}}</h5>
                        </div>
                      
                        <a href="{{URL('admin/user')}}" class="btn btn-default mb-0 mr-3 btn-sm text-white">Back to Users</a>
                    </div>
                  </div>
                  <div class="card-body pt-2">
                    <div class="table-responsive p-0">
                      <table class="table table-striped align-items-center mb-0">
                        <tbody>
                            <tr>
                                <th class="text-uppercase text-secondary text-xs  font-weight-bolder opacity-7 px-3">Name</th>
                                <td class="text-xs font-weight-bold px-3 w-100">{{$user['name']}}</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-secondary text-xs  font-weight-bolder opacity-7 px-3">Email</th>
                                <td class="text-xs font-weight-bold px-3 w-100">{{$user['email']}}</td>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-secondary text-xs  font-weight-bolder opacity-7 px-3">Roles</th>
                                <td class="px-3 d-block w-100">
                                    @foreach($user->getRoleNames() as $rk => $rv)  
                                        <span class="text-xs">{{$rv}}
                                        <a href="{{URL('admin/user/assignrolerevoke/'.$user['id'].'/'.$rv)}}" class="btn btn-sm btn-warning mb-0 ml-2 font-weight-light">Revoke Role</a></span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th class="text-uppercase text-secondary text-xs  font-weight-bolder opacity-7 px-3">Direct Permissions</th>
                                <td class="px-3 w-100">
                                    @foreach($user->getDirectPermissions() as $pk => $pv)  
                                        <span class="text-xs">{{$pv['name']}}
                                        <a href="{{URL('admin/user/assignpermissionrevoke/'.$user['id'].'/'.$pv['name'])}}" class="btn btn-sm btn-warning mb-0 ml-2 font-weight-light">Revoke Permission</a></span>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-12 col-xl-3 pr-0 d-none d-xl-block">
                {{-- @include('admin.inc.clockBar') --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    
@endpush