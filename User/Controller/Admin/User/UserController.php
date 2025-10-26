<?php

namespace App\Modules\User\Controller\Admin\User;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $data['users'] = User::paginate(10);
        return view('User::admin.user.index', $data);
    }

    public function show($id)
    {
        $data['user'] = User::find($id);
        return view('User::admin.user.show', $data);
    }

    public function assignpermission($user)
    {
        $data['user'] = User::find($user);
        $data['permissions'] = Permission::paginate(10);
        return view('User::admin.user.assignpermission', $data);
    }

    public function assignpermissionadd($user, $permission)
    {
        $user = User::find($user);
        $permission = Permission::findByName($permission);
        $user->givePermissionTo($permission['name']);
        return redirect()->back();
    }

    public function assignpermissionrevoke($user, $permission)
    {
        $user = User::find($user);
        $permission = Permission::findByName($permission);
        $user->revokePermissionTo($permission['name']);
        return redirect()->back();
    }
    public function assignrole($user)
    {
        $data['user'] = User::find($user);
        $data['roles'] = Role::paginate(10);
        return view('User::admin.user.assignrole', $data);
    }

    public function assignroleadd($user, $role)
    {
        $user = User::find($user);
        $role = Role::findByName($role);
        $user->assignRole($role['name']);
        return redirect()->back();
    }

    public function assignrolerevoke($user, $role)
    {
        $user = User::find($user);
        $role = Role::findByName($role);
        $user->removeRole($role['name']);
        return redirect()->back();
    }
}
