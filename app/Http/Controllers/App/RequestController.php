<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator, Session;

class RequestController extends Controller
{

    public function index()
    {
        $data['requests'] = \App\Models\Request::orderBy('id','desc')->get();
        return view('app.requests.index', $data);
    }

    public function create()
    {
        $data['categories'] = \App\Models\Category::all();
        return view('app.requests.create', $data);
    }

    public function store(Request $request)
    {
        $rules = [];

        $rules['category']      = 'required';
        $rules['name']          = 'required';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {
            $torrentRequest = \App\Models\Request::create([
                'category_id'       => $request->category,
                'name'              => $request->name,
                'description'       => $request->description,
                'request_by'        => Auth::user()->id,
            ]);

            $data['requests'] = \App\Models\Request::orderBy('id','desc')->get();

            Session::flash('success', $torrentRequest->name.' has been updated successfully!');

            return view('app.requests.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['categories'] = \App\Models\Category::all();
        $data['request']    = \App\Models\Request::find($id);
        return view('app.requests.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $fillRequest = \App\Models\Request::find($id);

        $rules = [];

        $rules['fill_request']     = 'required';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {
            $fillRequest->update([
                'is_filled'     => 1,
                'filled_url'    => $request->fill_request,
                'filled_by'     => Auth::user()->id,
            ]);

            $data['requests'] = \App\Models\Request::orderBy('id','desc')->get();
            return view('app.requests.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reset($id)
    {
        $torrentRequest = \App\Models\Request::find($id);

        Session::flash('success', 'Request '.$torrentRequest->name.' has been reset successfully!');

        $torrentRequest->update([
                'is_filled' => 0,
                'filled_url' => null,
                'filled_by' => null,
            ]);

        return back();
    }
    public function destroy($id)
    {
        $torrentRequest = \App\Models\Request::find($id);

        Session::flash('success', 'Request '.$torrentRequest->name.' has been deleted successfully!');

        $torrentRequest->delete();

        return back();
    }
}
