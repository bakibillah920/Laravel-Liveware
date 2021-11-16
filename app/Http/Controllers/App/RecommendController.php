<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Recommend;
use App\Models\Upload;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator, Session;

class RecommendController extends Controller
{
    /*public function index()
    {
        $data['recommends'] = Recommend::orderBy('id','desc')->get();
        return view('app.recommends.index', $data);
    }
    public function create()
    {
        $data['uploads'] = Upload::all();
        return view('app.recommends.create', $data);
    }*/
    public function store(Request $request)
    {

        $rules = [];

        $rules['torrent']       = 'required|unique:recommends,upload_id';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            $upload_id = $request->torrent;

            $recommend = Recommend::create([
                'upload_id'        => $upload_id,
                'is_recommended'   => 1,
            ]);

//            $data['recommends'] = Recommend::orderBy('id','desc')->get();

            $upload = Upload::find($recommend->upload_id);

            Session::flash('success', $upload->name.' has been recommended successfully!');

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

        $recommend = Recommend::find($id);

        $upload = Upload::find($recommend->upload_id);

        $recommend->delete();

        Session::flash('success', $upload->name.' has been UnRecommended successfully!');

    }
}
