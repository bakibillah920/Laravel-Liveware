<?php

namespace App\Http\Controllers\App\ACL;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Pm;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorizationController extends Controller
{
    public function view_user_roles()
    {
        
        if (Auth::user()->cannot('manage-user-role'))
        {
            return back();
        }
        
//        $data['users'] = User::all();
//        $data['roles'] = Role::all();

        if (Auth::user()->roles[0]->slug != 'developer')
        {
            $data['users'] = User::join('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('roles.slug', '!=', 'developer')
                ->where('roles.slug', '!=', 'admin')
                ->select('users.*')
                ->orderBy('users.id','desc')
                ->get();
            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->get();
        }
        else
        {
            $data['users'] = User::orderBy('id','desc')->get();
            $data['roles'] = Role::all();
        }

        return view('app.acl.authorizations.user-roles.index', $data);
    }

    public function update_user_roles(Request $request, $user_id, $role_id)
    {

        $user = User::find($user_id);
        $role = Role::find($role_id);

        if ($user->roles[0]->name != $role->name)
        {
            $system = User::where('username','=','System')->value('id');
            Pm::create([
                'sender_id' => $system,
                'receiver_id' => $user->id,
                'subject' => "Your rank has been changed",
                'description' => "Dear ".$user->username.", Your rank has been changed from ".$user->roles[0]->name." to ". $role->name,
            ]);
        }

        $user->roles()->detach();
        $user->roles()->attach($role_id);

//        $user->update(['role_id' => $role_id]);

        if (Auth::user()->roles[0]->slug != 'developer')
        {
            $data['users'] = User::join('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('roles.slug', '!=', 'developer')
                ->where('roles.slug', '!=', 'admin')
                ->select('users.*')
                ->orderBy('users.id','desc')
                ->get();
            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->get();
        }
        else
        {
            $data['users'] = User::orderBy('id','desc')->get();
            $data['roles'] = Role::all();
        }

        return view('app.acl.authorizations.user-roles.index', $data);

    }

    public function view_role_permissions()
    {
        
        if (Auth::user()->cannot('manage-role-permission'))
        {
            return back();
        }

        if (Auth::user()->roles[0]->slug != 'developer')
        {
//            $data['permissions'] = Permission::where('slug', '!=', 'manage-permission')->get();
            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->get();
            $data['permission_groups'] = Permission::where('parent_id','=',null)->where('slug', '!=', 'manage-permission')->get();

        }
        else
        {
//            $data['permissions'] = Permission::all();
            $data['roles'] = Role::all();
            $data['permission_groups'] = Permission::where('parent_id','=',null)->get();

        }
        return view('app.acl.authorizations.role-permissions.index', $data);
    }

    public function edit_role_permissions($role_id, $permission_id)
    {
//        return $role_id.'-'.$permission_id;
        $data['role'] = Role::find($role_id);
        $data['permission_parent'] = Permission::find($permission_id);
        $data['permissions'] = Permission::all();
        return view('app.acl.authorizations.role-permissions.edit', $data);
    }

    public function update_role_permissions(Request $request)
    {

        $role = Role::find($request->get('role_id'));
        $permission_group = Permission::find($request->permission_group_id);
        $permission_ids = $request->get('permission_id');

        if (count($permission_group->permissions) > 0)
        {
            foreach ($permission_group->permissions as $permission){
                $role->permissions()->detach($permission->id);
            }

            if ($permission_ids != '')
            {
                foreach ($permission_ids as $permission_id){
                    $role->permissions()->attach($permission_id);
                }
            }
        }
        else
        {
            $role->permissions()->detach($permission_group->id);
            $role->permissions()->attach($permission_ids);
        }


        if (Auth::user()->roles[0]->slug != 'developer')
        {
//            $data['permissions'] = Permission::where('slug', '!=', 'manage-permission')->get();
            $data['roles'] = Role::where('slug', '!=', 'developer')->get();
            $data['permission_groups'] = Permission::where('parent_id','=',null)->where('slug', '!=', 'manage-permission')->get();
        }
        else
        {
//            $data['permissions'] = Permission::all();
            $data['roles'] = Role::all();
            $data['permission_groups'] = Permission::where('parent_id','=',null)->get();
        }
        return view('app.acl.authorizations.role-permissions.index', $data);

    }

    public function view_user_permissions()
    {
        
        if (Auth::user()->cannot('manage-user-permission'))
        {
            return back();
        }
        
        if (Auth::user()->roles[0]->slug != 'developer')
        {
//            $data['permissions'] = Permission::where('slug', '!=', 'manage-permission')->get();
            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->get();
            $data['users'] = User::where('username', '!=', 'developer')->where('username', '!=', 'admin')->get();
            $data['permission_groups'] = Permission::where('parent_id','=',null)->where('slug', '!=', 'manage-permission')->get();
        }
        else
        {
//            $data['permissions'] = Permission::all();
            $data['roles'] = Role::all();
            $data['users'] = User::all();
            $data['permission_groups'] = Permission::where('parent_id','=',null)->get();
        }
        return view('app.acl.authorizations.user-permissions.index', $data);
    }

    public function edit_user_permissions($user_id, $permission_id)
    {
//        return $role_id.'-'.$permission_id;
        $data['user'] = User::find($user_id);
        $data['permission_parent'] = Permission::find($permission_id);
        $data['permissions'] = Permission::all();
        $data['permission_groups'] = Permission::where('parent_id','=',null)->get();
        return view('app.acl.authorizations.user-permissions.edit', $data);
    }

    public function update_user_permissions(Request $request)
    {

        $user = User::find($request->get('user_id'));

        $permission_group = Permission::find($request->permission_group_id);
        foreach ($permission_group->permissions as $permission){
            $user->permissions()->detach($permission->id);
        }

        $permission_ids = $request->get('permission_id');
        if ($permission_ids != '')
        {
            foreach ($permission_ids as $permission_id){
                $user->permissions()->attach($permission_id);
            }
        }

        if (Auth::user()->roles[0]->slug != 'developer')
        {
//            $data['permissions'] = Permission::where('slug', '!=', 'manage-permission')->get();
            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->get();
            $data['users'] = User::where('username', '!=', 'developer')->where('username', '!=', 'admin')->get();
            $data['permission_groups'] = Permission::where('parent_id','=',null)->where('slug', '!=', 'manage-permission')->get();
        }
        else
        {
//            $data['permissions'] = Permission::all();
            $data['roles'] = Role::all();
            $data['users'] = User::all();
            $data['permission_groups'] = Permission::where('parent_id','=',null)->get();
        }
        return view('app.acl.authorizations.user-permissions.index', $data);

    }
}
