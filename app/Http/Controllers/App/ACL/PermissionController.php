<?php

namespace App\Http\Controllers\App\ACL;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Validator;

class PermissionController extends Controller
{
    public function index()
    {
        $data['permissions'] = Permission::orderBy('id','desc')->get();
        return view('app.acl.permissions.index', $data);
    }

    public function create()
    {
        $data['permissions'] = Permission::all();
        return view('app.acl.permissions.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:255|unique:permissions',
        ]);

        if ($validator->passes())
        {

            Permission::create([
                'parent_id'   => $request->parent,
                'name'        => $request->name,
                'slug'        => Str::slug($request->name),
                'description' => $request->description,
            ]);

            $data['permissions'] = Permission::orderBy('id','desc')->get();
            return view('app.acl.permissions.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function show($id)
    {
        $data['permission'] = Permission::find($id);
        $data['permissions'] = Permission::all();
        return view('app.acl.permissions.show', $data);
    }

    public function edit($id)
    {
        $data['permission'] = Permission::find($id);
        $data['permissions'] = Permission::all();
        return view('app.acl.permissions.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $existing = Permission::find($id);
        if ($request->name != $existing->name)
        {
            $validator = Validator::make($request->all(), [
                'name'          => 'required|max:255|unique:permissions',
            ]);
        }
        else
        {
            $validator = Validator::make($request->all(), [
                'name'          => 'required|max:255',
            ]);
        }

        if ($validator->passes())
        {

            $existing->update([
                'parent_id'   => $request->parent,
                'name'        => $request->name,
                'slug'        => Str::slug($request->name),
                'description' => $request->description,
            ]);

            $data['permissions'] = Permission::orderBy('id','desc')->get();
            return view('app.acl.permissions.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function destroy($id)
    {
        Permission::find($id)->delete();
    }
}
