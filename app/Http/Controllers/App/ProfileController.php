<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;
use Validator, Session;

class ProfileController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($username)
    {

//        dd($username);

        $user = User::where('username', $username)->first();

//        dd($user);
        $data['user'] = $user;

        if ($user->id == Auth::user()->id)
        {
            $data['authUserUploads'] = Upload::where('uploads.created_by', $user->id)
                ->orderBy('uploads.created_at', 'desc')
//                ->join('approvals','uploads.id','approvals.upload_id')
//                ->where('approvals.is_approved','=','Approved')
                ->select('uploads.*')
                ->paginate(50);
        }
        $data['uploads'] = Upload::where('uploads.created_by', $user->id)
            ->orderBy('uploads.created_at', 'desc')
            ->join('approvals','uploads.id','approvals.upload_id')
            ->where('approvals.is_approved','=','Approved')
            ->select('uploads.*')
            ->paginate(50);
        return view('app.landing.profiles.show', $data);
    }

    public function edit($username)
    {
        $user = User::where('username', $username)->first();
        if (Auth::user()->id != $user->id)
        {
            return abort(403);
        }
        $data['user'] = User::where('username', $username)->first();
        return view('app.landing.profiles.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $rules = [];

        $user = User::find($id);

        /*if ($user->username != $request->username)
        {
            $rules['username']  = 'required|string|max:255|unique:users';
        }*/
        if ($user->email != $request->email)
        {
            $rules['email']  = 'required|string|max:255|unique:users';
        }
        if ($request->hasFile('avatar'))
        {
            $rules['avatar']    = 'required|mimes:jpeg,jpg,gif,png';
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            if ($request->hasFile('avatar'))
            {
                $file = $request->file('avatar');
                $extension = $file->getClientOriginalExtension();
                $filename  = time() . '.' . $extension;
                $file = Image::make($request->file('avatar')->getRealPath());
                $file->resize(150, 150);
//                $file->move(public_path('images/avatars/'), $filename);
                $file->save(public_path('images/avatars/'.$filename));
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
//                'username'      => $request->username,
                'email'         => $request->email,
                'gender'        => $request->input('gender'),
                'dob'           => $request->dob,
                'updated_by'    => Auth::user()->id,
            ]);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function destroy($id)
    {
        //
    }

    public function editPassword()
    {
        return view('app.landing.profiles.updatePassword');
    }

    public function updatePassword(Request $request)
    {

        $rules = [];

        $rules['oldPassword']  = 'required';
        $rules['password']  = 'required|confirmed';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {
            $oldPassword = Hash::check($request->oldPassword, Auth::user()->getAuthPassword());

            if ($oldPassword == 'true')
            {
                User::find(Auth::user()->id)->update([
                    'password' => Hash::make($request->password),
                ]);

                return response()->json(['success'=>['Updated successfully!']]);
//                return view('app.landing.profiles.updatePassword');
            }
            else
            {
                return response()->json(['error'=>['Old password do not match!']]);
            }
        }
        return response()->json(['error'=>$validator->errors()->all()]);
    }
}
