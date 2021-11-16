<?php

namespace App\Http\Controllers\App\ACL;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Validator;

class RoleController extends Controller
{
    public function index()
    {
        if (Auth::user()->roles[0]->slug != 'developer')
        {
            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->orderBy('id','desc')->get();
        }
        else
        {
            $data['roles'] = Role::all();
        }


        return view('app.acl.roles.index', $data);
    }

    public function create()
    {
        return view('app.acl.roles.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:255|unique:roles',
            'color'         => 'required|unique:roles',
        ]);

        if ($validator->passes())
        {

            Role::create([
                'name'        => $request->name,
                'slug'        => Str::slug($request->name),
                'color'       => $request->color,
                'description' => $request->description,
            ]);

            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->orderBy('id','desc')->get();
            return view('app.acl.roles.index', $data);

        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function show($id)
    {
        $data['role'] = Role::find($id);
        return view('app.acl.roles.show', $data);
    }

    public function edit($id)
    {
        $data['role'] = Role::find($id);
        return view('app.acl.roles.edit', $data);
    }

    public function update(Request $request, $id)
    {

        $rules = [];

        $existing = Role::find($id);


        $rules['description']      = 'required';
        if ($request->name != $existing->name)
        {
            $rules['name']      = 'required|max:255|unique:roles';
        }

        if ($request->color != $existing->color)
        {
            $rules['color']     = 'required|max:255|unique:roles';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            Role::findOrFail($id)->update([
                'name'        => $request->name,
                'slug'        => Str::slug($request->name),
                'color'       => $request->color,
                'description' => $request->description,
            ]);

            $data['roles'] = Role::where('slug', '!=', 'developer')->where('slug', '!=', 'admin')->orderBy('id','desc')->get();
            return view('app.acl.roles.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function destroy($id)
    {
        Role::find($id)->delete();
    }
}
