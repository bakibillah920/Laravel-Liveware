<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Upcoming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Validator, Session;

class UpcomingController extends Controller
{
    public function index()
    {
        $data['upcomings'] = Upcoming::orderBy('id','desc')->get();
        return view('app.upcomings.index', $data);
    }
    public function create()
    {
//        $data['categories'] = Category::where(['parent_id' => ''])->get();
        $data['categories'] = Category::all();
        return view('app.upcomings.create', $data);
    }
    public function check_imdb(Request $request)
    {
        $IMDB = new \IMDB($request->imdbURL);
        if ($IMDB->isReady) {
            $res = 'Match found';
            $title = $IMDB->getTitle();
            return response()->json($title);
        } else {
            $res = 'Movie not found. 😞';
            $title = $IMDB->getTitle();
            return response()->json($title);
        }
    }
    public function store(Request $request)
    {
        $rules = [];

        $rules['category']      = 'required';
        $rules['name']          = 'required';
        $rules['image']         = 'required|mimes:png,jpg,jpeg,webp,gif|max:100';
        $rules['description']   = 'required';
        $rules['upload_date']   = 'required';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            $category = Category::find($request->category);

            $image    = $request->file('image');
            $imageFile   = 'Top10Torrent.site-'.'-'.$image->getClientOriginalName();
            $imageFile = preg_replace('/\s+/', '', $imageFile);

            $image = Image::make($request->file('image')->getRealPath());
            $image->resize(250, 320);
            $image->save(public_path('upcomings/'.$imageFile));

            $upcoming = Upcoming::create([
                'category_id'       => $request->category,
                'name'              => $request->name,
                'slug'              => Str::slug($request->name),
                'imdbURL'           => $request->imdbURL,
                'upload_date'       => $request->upload_date,
                'image'             => $imageFile,
                'description'       => $request->description,
            ]);

            $data['upcomings'] = Upcoming::orderBy('id','desc')->get();

            Session::flash('success', $upcoming->name.' has been created successfully!');

            return view('app.upcomings.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }
    public function show($id)
    {
        $data['upcoming'] = Upcoming::find($id);
        $data['categories'] = Category::all();
        return view('app.upcomings.show', $data);
    }
    public function edit($id)
    {
        $data['upcoming'] = Upcoming::find($id);
        $data['categories'] = Category::all();
        return view('app.upcomings.edit', $data);
    }
    public function update(Request $request, $id)
    {
        $rules = [];

        $rules['category']      = 'required';
        $rules['name']          = 'required';
        $rules['description']   = 'required';
        $rules['upload_date']   = 'required';

        if ($request->hasFile('image'))
        {
            $rules['image']         = 'required|mimes:png,jpg,jpeg,webp,gif|max:100';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            $category = Category::find($request->category);

            $upcoming = Upcoming::find($id);

            if ($request->hasFile('image'))
            {
                $image    = $request->file('image');
                $imageFile   = 'Top10Torrent.site-'.$image->getClientOriginalName();
                $imageFile = preg_replace('/\s+/', '', $imageFile);

                $image = Image::make($request->file('image')->getRealPath());
                $image->resize(250, 320);
                $image->save(public_path('upcomings/'.$imageFile));

                $upcoming->update([
                    'image'           => $imageFile,
                ]);
            }

            $upcoming->update([
                'category_id'       => $request->category,
                'name'              => $request->name,
                'slug'              => Str::slug($request->name),
                'imdbURL'           => $request->imdbURL,
                'upload_date'       => $request->upload_date,
                'description'       => $request->description,
            ]);

            $data['upcomings'] = Upcoming::orderBy('id','desc')->get();

            Session::flash('success', $upcoming->name.' has been updated successfully!');

            return view('app.upcomings.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }
    public function destroy($id)
    {
        Upcoming::find($id)->delete();
    }
}
