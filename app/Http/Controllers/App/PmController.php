<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Pm;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Validator, Session;

class PmController extends Controller
{

    public function index()
    {
        $data['pms'] = Pm::where('receiver_id', Auth::user()->id)->latest()->get();
        return view('app.landing.pms.index', $data);
    }

    public function outbox()
    {
        $data['pms'] = Pm::where('sender_id', Auth::user()->id)->latest()->get();
        return view('app.landing.pms.outbox', $data);
    }

    public function create()
    {
        if (Auth::user()->roles[0]->slug != 'developer')
        {
            $data['users'] = User::join('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('roles.slug', '!=', 'developer')
                ->where('users.username', '!=', 'System')
                ->select('users.*')
                ->get();
        }
        else
        {
            $data['users'] = User::all();
        }
        return view('app.landing.pms.create', $data);
    }

    public function sendMail($id)
    {
        if (Auth::user()->roles[0]->slug != 'developer')
        {
            $data['users'] = User::join('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('roles.slug', '!=', 'developer')
                ->where('users.username', '!=', 'System')
                ->select('users.*')
                ->get();
        }
        else
        {
            $data['users'] = User::all();
        }
        $data['receiver'] = User::find($id);

        return view('app.landing.pms.sendMail', $data);
    }

    public function replayMail($user_id, $replay_id)
    {
        if (Auth::user()->roles[0]->slug != 'developer')
        {
            $data['users'] = User::join('role_user', 'users.id', '=', 'role_user.user_id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('roles.slug', '!=', 'developer')
                ->where('users.username', '!=', 'System')
                ->select('users.*')
                ->get();
        }
        else
        {
            $data['users'] = User::all();
        }
        $data['receiver'] = User::find($user_id);
        $data['replay_id'] = $replay_id;
        $data['pms'] = Pm::where('replay_id', $replay_id)->get();

        return view('app.landing.pms.replayMail', $data);
    }

    public function store(Request $request)
    {
        $system = User::where('username', 'System')->value('id');

//        dd(count($request->receiver));
        $rules = [];

        $rules['receiver']       = 'required';
        $rules['subject']        = 'required|string|max:255';
        $rules['description']    = 'required|string';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            foreach ($request->receiver as $receiver_id)
            {

                if ($receiver_id == $system)
                {
                    return back()->with('error', 'No user exists with this name!');
                }


                if ($request->replay_id != '')
                {
                    Pm::create([
                        'replay_id'         => $request->replay_id,
                        'sender_id'         => Auth::user()->id,
                        'receiver_id'       => $receiver_id,
                        'subject'           => $request->subject,
                        'description'       => $request->description,
                    ]);
                }
                else
                {
                    Pm::create([
                        'sender_id'         => Auth::user()->id,
                        'receiver_id'       => $receiver_id,
                        'subject'           => $request->subject,
                        'description'       => $request->description,
                    ]);
                }
            }

            if (count($request->receiver) < 2)
            {
                if (Auth::user()->roles[0]->slug != 'developer')
                {
                    $data['users'] = User::join('role_user', 'users.id', '=', 'role_user.user_id')
                        ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                        ->where('roles.slug', '!=', 'developer')
                        ->where('users.username', '!=', 'System')
                        ->select('users.*')
                        ->get();
                }
                else
                {
                    $data['users'] = User::all();
                }
                $data['receiver'] = User::find($request->receiver[0]);

                $data['old_pms'] = Pm::where('sender_id', Auth::user()->id)->where('receiver_id', $request->receiver[0])->get();

                Session::flash('success', 'PM has been sent successfully!');

                return view('app.landing.pms.sendMail', $data);
            }

            $data['pms'] = Auth::user()->pms;

            Session::flash('success', 'PM has been sent successfully!');

            if ($request->replay_id != '')
            {
                dd('replay');
            }

            return view('app.landing.pms.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function read($id)
    {
        Pm::find($id)->update([
            'read_at' => Carbon::now()
        ]);
        $data['pms'] = Auth::user()->pms;
        return redirect('/mail-box');
    }

    public function readAll()
    {
//        dd('readAll');
        $unreads = Pm::where('receiver_id', Auth::user()->id)->where('read_at', '=', null)->get();

//        dd(count($unreads));
        foreach ($unreads as $unread)
        {
            $unread->update([
                'read_at' => Carbon::now()
            ]);
        }
        $data['pms'] = Auth::user()->pms;
        Session::flash('success', 'Marked all as read successfully!');
        return back();
    }

    public function show($id)
    {
        $pm = Pm::find($id);

        if ($pm->read_at == '')
        {
            Pm::find($id)->update([
                'read_at' => Carbon::now()
            ]);
            Session::flash('success', 'Marked as read successfully!');
        }

        $data['pm'] = Pm::find($id);

        return view('app.landing.pms.show', $data);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        Pm::find($id)->delete();

        Session::flash('success', 'PM has been deleted successfully!');
    }

}
