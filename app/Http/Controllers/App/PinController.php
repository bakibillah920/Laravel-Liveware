<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Pin;
use App\Models\Upload;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator, Session;

class PinController extends Controller
{
    public function index()
    {
        $data['pins'] = Pin::orderBy('id','desc')->get();
        return view('app.pins.index', $data);
    }
    public function create()
    {
        $data['uploads'] = Upload::all();
        return view('app.pins.create', $data);
    }
    public function store(Request $request)
    {

        $rules = [];

        $rules['torrent']       = 'required|unique:pins,upload_id';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            $upload_id = $request->torrent;

            Pin::create([
                'upload_id'        => $upload_id,
                'is_pinned'        => 1,
            ]);

            $data['pins'] = Pin::orderBy('id','desc')->get();

            $upload = Upload::find($upload_id);

            Session::flash('success', $upload->name.' has been pinned successfully!');

//            return view('app.pins.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
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
        $pin = Pin::find($id);

        $upload = Upload::find($pin->upload_id);

        Session::flash('success', $upload->name.' has been unpinned successfully!');

        $pin->delete();
    }
}
