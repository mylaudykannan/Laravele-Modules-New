<?php

namespace App\Modules\User\Controller\Admin\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $data['roles'] = Role::paginate(10);
        return view('User::admin.role.index', $data);
    }

    public function create()
    {
        return view('User::admin.role.create');
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $role = Role::create(['name' => $name]);
        return redirect('admin/role');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect('admin/role');
    }

    public function assignpermission($role)
    {
        $data['role'] = Role::findByName($role);
        $data['permissions'] = Permission::paginate(10);
        return view('User::admin.role.assignpermission', $data);
    }

    public function assignpermissionadd($role, $permission)
    {
        $role = Role::findByName($role);
        $permission = Permission::findByName($permission);
        $permission->assignRole($role);
        return redirect()->back();
    }

    public function assignpermissionrevoke($role, $permission)
    {
        $role = Role::findByName($role);
        $permission = Permission::findByName($permission);
        $role->revokePermissionTo($permission);
        return redirect()->back();
    }
}
