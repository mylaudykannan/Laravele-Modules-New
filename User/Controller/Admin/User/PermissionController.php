<?php

namespace App\Modules\User\Controller\Admin\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $data['permissions'] = Permission::paginate(10);
        return view('User::admin.permission.index', $data);
    } 

    public function create()
    {
        return view('User::admin.permission.create');
    }

    public function store(Request $request)
    {
        $name = $request->name;
        $permission = Permission::create(['name' => $name]);
        return redirect('admin/permission');
    }

    public function destroy($id)
    {
        $perm = Permission::findOrFail($id);
        $perm->delete();
        return redirect('admin/permission');
    }

    public function assignrole($permission)
    {
        $data['permission'] = Permission::findByName($permission);
        $data['roles'] = Role::paginate(10);
        return view('User::admin.permission.assignrole', $data);
    }

    public function assignroleadd($permission, $role)
    {
        $permission = Permission::findByName($permission);
        $role = Role::findByName($role);
        $role->givePermissionTo($permission);
        return redirect()->back();
    }

    public function assignrolerevoke($permission, $role)
    {
        $permission = Permission::findByName($permission);
        $role = Role::findByName($role);
        $permission->removeRole($role);
        return redirect()->back();
    }
}
