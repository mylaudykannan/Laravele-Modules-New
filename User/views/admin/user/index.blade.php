@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-xl-9">
                <div class="card customCard mb-4">
                    <div class="card-header p-0">
                        <div class="pt-4 pb-3">
                            <div class="mb-3">
                                <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                                    <li class="breadcrumb-item"><a href="javascript:;">Application</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:;">Dashboard</a></li>
                                </ol>
                            </div>
                            <h5 class="text-capitalize pl-3 mb-0">Users</h5>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive p-0">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th>Direct Permissions</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $k => $v)
                                        <tr>
                                            <td>{{ $users->firstItem() + $k }}</td>
                                            <td>{{ $v['name'] }}</td>
                                            <td>{{ $v['email'] }}</td>
                                            <td>
                                                @foreach ($v->getRoleNames() as $rk => $rv)
                                                    <span class="text-xs">{{ $rv }} <a
                                                            href="{{ URL('admin/user/assignrolerevoke/' . $v['id'] . '/' . $rv) }}"
                                                            class="btn btn-sm btn-warning ml-2">Revoke Role</a></span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($v->getDirectPermissions() as $pk => $pv)
                                                    <span class="">{{ $pv['name'] }} <a
                                                            href="{{ URL('admin/user/assignpermissionrevoke/' . $v['id'] . '/' . $pv['name']) }}"
                                                            class="btn btn-sm btn-warning">Revoke Permission</a></span>
                                                @endforeach
                                            </td>
                                            <td><a href="{{ URL('admin/user/assignrole/' . $v['id']) }}"
                                                    class="btn btn-sm btn-success">Roles</a>&nbsp;&nbsp;<a
                                                    href="{{ URL('admin/user/assignpermission/' . $v['id']) }}"
                                                    class="btn btn-sm btn-success">Permissions</a>&nbsp;&nbsp;<a
                                                    href="{{ URL('admin/user/view/' . $v['id']) }}"
                                                    class="btn btn-sm btn-success">View</a></td>
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
                                {{$users->appends(request()->input())->links('pagination.admin')}}
                            </div>
                        </nav>
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