<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Upcoming;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Validator;
use Intervention\Image\ImageManagerStatic as Image;

class CategoryController extends Controller
{
    public function index()
    {
        $data['categories'] = Category::orderBy('id','desc')->get();
        return view('app.categories.index', $data);
    }
    public function create()
    {
        $data['categories'] = Category::all();
        $data['lastSerial'] = Category::latest()->value('serial');
        return view('app.categories.create', $data);
    }
    public function store(Request $request)
    {
        $rules = [];

        $rules['name']          = 'required';
        $rules['serial']        = 'required';

        if ($request->file('icon') != '')
        {
            $file = $request->file('icon');
            $extension = $file->getClientOriginalExtension();
            if ($extension != 'svg' || $extension != 'webp')
            {
                $rules['icon']         = 'required|mimes:jpeg,jpg,gif,png,svg,webp|max:100';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            if ($request->hasFile('icon') != '')
            {
                $file = $request->file('icon');
                $extension = $file->getClientOriginalExtension();
                $filename  = $request->name . '.' . $extension;

                $file = Image::make($request->file('icon')->getRealPath());
                $file->resize(25, 25);
//                $file->move(public_path('images/categories/'), $filename);
                $file->save(public_path('images/categories/'.$filename));


            }
            else
            {
                $filename = '';
            }

            $category = Category::create([
                'parent_id'     => $request->parent_id,
                'name'          => $request->name,
                'slug'          => Str::slug($request->name),
                'icon'          => $filename,
                'serial'        => $request->serial,
                'status'        => $request->status,
            ]);

            // Create NavCategory and its attributes
            // NavCategory Permission is created on PermissionSeeder...

            $NavCategory = Permission::where('slug', '=', 'navcategory')->first();
            $permission = Permission::create([
                'parent_id'     => $NavCategory->id,
                'name'          => 'NavCategory '.$category->name,
                'slug'          => 'navcategory-'.$category->slug,
                'description'   => null,
            ]);

            $roles = Role::all();
            foreach($roles as $role)
            {
                foreach($role->permissions as $role_permission)
                {
                    if ($role_permission->slug == 'navcategory-autoassign')
                    {
                        $role->permissions()->attach($permission->id);
                    }
                }
            }

            $data['categories'] = Category::orderBy('id','desc')->get();
            return view('app.categories.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }
    public function show($id)
    {
        $data['category'] = Category::find($id);
        $data['categories'] = Category::all();
        return view('app.categories.show', $data);
    }
    public function edit($id)
    {
        $data['category'] = Category::find($id);
        $data['categories'] = Category::all();
        return view('app.categories.edit', $data);
    }
    public function update(Request $request, $id)
    {

        $category = Category::find($id);

        $rules = [];

        $rules['name']          = 'required';
        $rules['serial']        = 'required';

        if ($request->file('icon') != '')
        {
            $file = $request->file('icon');
            $extension = $file->getClientOriginalExtension();
            if ($extension != 'svg' || $extension != 'webp')
            {
                $rules['icon']         = 'required|mimes:jpeg,jpg,gif,png,svg,webp';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            if ($request->hasFile('icon') != '')
            {
                $file = $request->file('icon');
                $extension = $file->getClientOriginalExtension();
                $filename  = $request->name . '.' . $extension;

                $file = Image::make($request->file('icon')->getRealPath());
                $file->resize(25, 25);
//                $file->move(public_path('images/categories/'), $filename);
                $file->save(public_path('images/categories/'.$filename));

                $category->update([
                    'icon'          => $filename,
                ]);

            }

            // Update NavCategory attributes
            Permission::where('slug','=','navcategory-'.$category->slug)->first()->update([
                'name'          => 'NavCategory '.$request->name,
                'slug'          => 'navcategory-'.Str::slug($request->name),
            ]);

            $category->update([
                'parent_id'     => $request->parent_id,
                'name'          => $request->name,
                'slug'          => Str::slug($request->name),
                'serial'        => $request->serial,
                'status'        => $request->status,
            ]);

            $data['categories'] = Category::orderBy('id','desc')->get();
            return view('app.categories.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }
    public function destroy($id)
    {

        $category = Category::find($id);

        // Delete NavCategory attributes
        $permissions = Permission::where('slug','=','navcategory-'.$category->slug)->first();
        if ($permissions)
        {
            $permissions->delete();
        }

        $upload = Upload::where('category_id', $id)->first();
        if ($upload)
        {
            $upload->delete();
        }
        $upcoming = Upcoming::where('category_id', $id)->first();
        if ($upcoming)
        {
            $upcoming->delete();
        }
        $requests = \App\Models\Request::where('category_id', $id)->first();
        if ($requests)
        {
            $requests->delete();
        }



        $category->delete();

    }
}
