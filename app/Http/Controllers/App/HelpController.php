<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Help;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator, Session;

class HelpController extends Controller
{

    public function index()
    {
        if (Auth::user()->can('manage-helpdesk-view-all-question'))
        {
            $data['helps'] = Help::orderBy('id','desc')->get();
        }
        else
        {
            $data['helps'] = Help::orderBy('id','desc')->where('asked_by', Auth::user()->id)->get();
        }
        return view('app.landing.helpdesk.index', $data);
    }

    public function create()
    {
        $data['users'] = User::all();
        return view('app.landing.helpdesk.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [];

        $rules['subject']      = 'required|string|max:255';
        $rules['question']     = 'required|string';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {
            $help = Help::create([
                'subject'        => $request->subject,
                'question'       => $request->question,
                'asked_by'       => Auth::user()->id,
            ]);

            $data['helps'] = Help::orderBy('id','desc')->get();

            Session::flash('success', $help->subject.' has been created successfully!');

            return view('app.landing.helpdesk.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    public function show($id)
    {
        Help::find($id)->update([
            'read_at'         => Carbon::now(),
        ]);
        $data['help']    = Help::find($id);
        return view('app.landing.helpdesk.show', $data);
    }

    public function edit($id)
    {
        $data['help']    = Help::find($id);
        return view('app.landing.helpdesk.edit', $data);
    }

    public function update(Request $request, $id)
    {

        $fillQuestion = Help::find($id);

        $rules = [];

        $rules['answer']     = 'required|string';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {
            $fillQuestion->update([
                'is_answered'     => 1,
                'answer'          => $request->answer,
                'answered_by'     => Auth::user()->id,
                'read_at'         => Carbon::now(),
            ]);

            $data['helps'] = Help::orderBy('id','desc')->get();

            Session::flash('success', $fillQuestion->subject.' has been updated successfully!');

            return view('app.landing.helpdesk.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);

    }

    public function destroy($id)
    {
        Help::find($id)->delete();
    }
}
