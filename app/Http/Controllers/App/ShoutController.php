<?php

namespace App\Http\Controllers\App;

use App\Events\ShoutEvent;
use App\Http\Controllers\Controller;
use App\Models\Shout;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator;
use Session;

class ShoutController extends Controller
{
    public function index()
    {

        $data['shouts'] = \App\Models\Shout::all();

        return view('app.landing.shouts.index', $data);

        /*return response()->json(Shout::with('user')->latest()->get());*/

    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {

        /*$shout = Shout::create([
            'comment' => $request->shout,
            'user_id' => Auth::user()->id,
        ]);

        $shout = Shout::where('id', $shout->id)->first();

//        broadcast(new ShoutEvent($shout))->toOthers();
        event(new ShoutEvent($shout));

        return $shout->toJson();*/

        $rules = [];

        $rules['message']       = 'required';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            $shout = Shout::create([
                'comment' => $request->message,
                'user_id' => Auth::user()->id,
            ]);

            $data['latest'] = Shout::find($shout->id);
            $data['comment'] = Shout::orderBy('id','desc')->get();
//            $data['comment'] = Shout::all();

            return view('app.landing.shouts.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }
    public function newShout(Request $request)
    {
        $maxShout = $request->maxShout;
        $exists = Shout::latest()->first();

        if (Auth::check()== true)
        {
            if ($exists->id > $maxShout && $exists->user_id != Auth::user()->id)
            {
                $data['hasNew'] = 'true';
                $data['newShouts'] = Shout::where('shouts.id', '>', $maxShout)->get();
                return response($data);
//            return view('app.landing.shouts.index', $data);
            }
            else
            {
                $data['hasNew'] = 'false';
                return response($data);
//            return view('app.landing.shouts.index', $data);
            }
        }
        else
        {
            if ($exists->id > $maxShout)
            {
                $data['hasNew'] = 'true';
                $data['newShouts'] = Shout::where('shouts.id', '>', $maxShout)->get();
                return response($data);
//            return view('app.landing.shouts.index', $data);
            }
            else
            {
                $data['hasNew'] = 'false';
                return response($data);
//            return view('app.landing.shouts.index', $data);
            }
        }


    }
    public function delete()
    {
        Shout::truncate();
        return back();
    }
    public function deleteSingle($id)
    {
        $shout = Shout::find($id);
        $shout->delete();
        Session::flash('success', '"'.$shout->comment.'" has been deleted successfully!');
        return back();
    }

    public function show($id)
    {
        //
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
        //
    }
}
