<?php

namespace App\Http\Controllers\App;

use App\Helpers\TorrentRW;
use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Category;
use App\Models\ImdbDetail;
use App\Models\ImdbKey;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Validator, Session;

class MyUploadController extends Controller
{
    public function index()
    {
        $data['uploads'] = Upload::where('created_by', Auth::user()->id)->orderBy('id','desc')->paginate(25);
        return view('app.my-uploads.index', $data);
    }
    public function allApproved()
    {
        $data['uploads'] = Upload::join('approvals', 'approvals.upload_id', 'uploads.id')->where('approvals.is_approved', 'Approved')->where('uploads.created_by', Auth::user()->id)->orderBy('uploads.id','desc')->paginate(25);
        return view('app.my-uploads.approved', $data);
    }
    public function allDisapproved()
    {
        $data['uploads'] = Upload::join('approvals', 'approvals.upload_id', 'uploads.id')->where('approvals.is_approved', 'Disapproved')->where('uploads.created_by', Auth::user()->id)->orderBy('uploads.id','desc')->paginate(25);
        return view('app.my-uploads.disapproved', $data);
    }
    public function allPending()
    {
        $data['uploads'] = Upload::join('approvals', 'approvals.upload_id', 'uploads.id')->where('approvals.is_approved', 'Pending')->where('uploads.created_by', Auth::user()->id)->orderBy('uploads.id','desc')->paginate(25);
        return view('app.my-uploads.pending', $data);
    }
    public function create()
    {
//        $data['categories'] = Category::where(['parent_id' => ''])->get();
        $data['categories'] = Category::all();
        return view('app.my-uploads.create', $data);
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
        $rules['torrent']       = 'required|mimes:torrent|max:2048';
        $rules['image']         = 'required|mimes:png,jpg,jpeg,webp,gif|max:1024';
        $rules['description']   = 'required';

        if (Upload::where('slug', Str::slug($request->name))->first())
        {
            return response()->json(['error'=>['Torrent already exists with this name!']]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $category = Category::find($request->category);

            $torrent = $request->file('torrent');

            $torrentRW = new TorrentRW($torrent);
            $torrentRW->announce('udp://tracker.top10torrent.site:2020/announce');

            $torrentFile   = 'Top10Torrent.site-'.Str::slug($request->name).'.'.$torrent->getClientOriginalExtension();
//            $torrentFile = preg_replace('/\s+/', '', $torrentFile);
//            $torrent->move(public_path('torrents/' . $category->parentCategory->slug . '/' . $category->slug . '/'), $torrentFile);
            $torrent->move(public_path('torrents/'), $torrentFile);

            $torrentRW->save(public_path('torrents/'.$torrentFile));

            $image = $request->file('image');
            $imageFile   = 'Top10Torrent.site-'.Str::slug($request->name).'.'.$image->getClientOriginalExtension();
            /*$image->move(public_path('torrents/images/'.$category->parentCategory->slug.'/'.$category->slug.'/'), $imageFile);*/

            $image = Image::make($request->file('image')->getRealPath());
            $image->resize(250, 320);
//            $image->save(public_path('torrents/' . $category->parentCategory->slug . '/' . $category->slug . '/' . $imageFile));
            $image->save(public_path('torrents/' . $imageFile));

            $upload = Upload::create([
                'category_id'   => $request->category,
                'name'          => $request->name,
                'slug'          => Str::slug($request->name),
                'imdbURL'       => $request->imdbURL,
                'torrent'       => $torrentFile,
                'image'         => $imageFile,
                'description'   => $request->description,
                'resolution'    => $request->resolution,
                'is_anonymous'     => $request->is_anonymous,
            ]);

            if ($request->imdbURL != '') {
                ImdbKey::create([
                    'upload_id' => $upload->id,
                    'key' => $request->imdbURL,
                ]);
            }

            if ($upload->imdbKey)
            {
                $IMDB = new \IMDB($upload->imdbKey->key);
                ImdbDetail::create([
                    'upload_id'         =>$upload->id,
                    'rating'            =>$IMDB->getRating(),
                    'release_date'      =>$IMDB->getReleaseDate(),
                    'genre'             =>$IMDB->getGenre(),
                    'director'          =>$IMDB->getDirector(),
                    'awards'            =>$IMDB->getAwards(),
                    'description'       =>$IMDB->getDescription(),
                ]);
            }

            if (Auth::user()->can('upload-approval-pending'))
            {
                $is_approved = 'Pending';
            }
            elseif (Auth::user()->can('upload-approval-approve'))
            {
                $is_approved = 'Approved';
            }
            elseif (Auth::user()->can('upload-approval-disapprove'))
            {
                $is_approved = 'Disapprove';
            }
            else
            {
                $is_approved = 'Pending';
            }

            Approval::create([
                'upload_id'     => $upload->id,
                'is_approved'   => $is_approved,
            ]);

            $data['uploads'] = Upload::where('created_by', Auth::user()->id)->orderBy('id','desc')->paginate(25);

            Session::flash('success', $upload->name.' has been uploaded successfully!');


            return view('app.my-uploads.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }
    public function show($id)
    {
        $data['upload'] = Upload::find($id);
        $data['categories'] = Category::all();
        return view('app.my-uploads.show', $data);
    }
    public function edit($id)
    {
        $data['upload'] = Upload::find($id);
        $data['categories'] = Category::all();
        return view('app.my-uploads.edit', $data);
    }
    public function update(Request $request, $id)
    {

        $category = Category::find($request->category);

        $upload = Upload::find($id);

        $rules = [];

        $rules['category']      = 'required';
        $rules['name']          = 'required';
        $rules['description']   = 'required';

        if ($request->hasFile('torrent'))
        {
            $rules['torrent']       = 'required|mimes:torrent|max:2048';
        }

        if ($request->hasFile('image'))
        {
            $rules['image']         = 'required|mimes:png,jpg,jpeg,webp,gif|max:1024';
        }

        if ($upload->name != $request->name)
        {
            $rules['name']          = 'unique:uploads';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            if ($request->hasFile('torrent'))
            {
                $torrent    = $request->file('torrent');

                $torrentRW = new TorrentRW($torrent);
                $torrentRW->announce('udp://tracker.top10torrent.site:2020/announce');

                $torrentFile   = 'Top10Torrent.site-'.Str::slug($request->name).'.'.$torrent->getClientOriginalExtension();
//                $torrentFile = preg_replace('/\s+/', '', $torrentFile);
//                $torrent->move(public_path('torrents/'.$category->parentCategory->slug.'/'.$category->slug.'/'), $torrentFile);
                $torrent->move(public_path('torrents/'), $torrentFile);

                $torrentRW->save(public_path('torrents/'.$torrentFile));

                $upload->update([
                    'torrent'           => $torrentFile,
                ]);
            }

            if ($request->hasFile('image'))
            {
                $image    = $request->file('image');

                $imageFile   = 'Top10Torrent.site-'.Str::slug($request->name).'.'.$image->getClientOriginalExtension();
                /*$image->move(public_path('torrents/images/'.$category->parentCategory->slug.'/'.$category->slug.'/'), $imageFile);*/

                $image = Image::make($request->file('image')->getRealPath());
                $image->resize(250, 320);
//                $image->save(public_path('torrents/'.$category->parentCategory->slug.'/'.$category->slug.'/'.$imageFile));
                $image->save(public_path('torrents/'.$imageFile));

                $upload->update([
                    'image'           => $imageFile,
                ]);
            }

            if(\File::exists(public_path('torrents/'.$upload->torrent))){
                $ext = \File::extension('torrents/'.$upload->torrent);
                public_path(rename('torrents/'.$upload->torrent, 'torrents/'.'Top10Torrent.site-'.Str::slug($request->name).'.'.$ext));
                $upload->update([
                    'torrent'           => 'Top10Torrent.site-'.Str::slug($request->name).'.'.$ext,
                ]);
            }

            if(\File::exists(public_path('torrents/'.$upload->image))){
                $ext = \File::extension('torrents/'.$upload->image);
                public_path(rename('torrents/'.$upload->image, 'torrents/'.'Top10Torrent.site-'.Str::slug($request->name).'.'.$ext));
                $upload->update([
                    'image'             => 'Top10Torrent.site-'.Str::slug($request->name).'.'.$ext,
                ]);
            }

            $upload->update([
                'category_id'       => $request->category,
                'name'              => $request->name,
                'slug'              => Str::slug($request->name),
                'imdbURL'           => $request->imdbURL,
                'description'       => $request->description,
                'resolution'        => $request->resolution,
                'is_anonymous'      => $request->is_anonymous,
            ]);

            if ($request->imdbURL != '')
            {
                ImdbKey::where('upload_id', $upload->id)->update([
                    'key'       => $request->imdbURL,
                ]);
            }

            if ($upload->imdbKey)
            {
                $IMDB = new \IMDB($upload->imdbKey->key);
                $upload->imdbDetail()->update([
                    'upload_id'         =>$upload->id,
                    'rating'            =>$IMDB->getRating(),
                    'release_date'      =>$IMDB->getReleaseDate(),
                    'genre'             =>$IMDB->getGenre(),
                    'director'          =>$IMDB->getDirector(),
                    'awards'            =>$IMDB->getAwards(),
                    'description'       =>$IMDB->getDescription(),
                ]);
            }

            if ($request->is_approved != '')
            {
                Approval::where('upload_id', $upload->id)->update([
                    'is_approved'           => $request->is_approved,
                    'disapproved_reason'    => $request->disapproved_reason,
                ]);
            }

            $data['uploads'] = Upload::where('created_by', Auth::user()->id)->orderBy('id','desc')->paginate(25);

            Session::flash('success', $upload->name.' has been updated successfully!');



            return view('app.my-uploads.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }
    public function destroy($id)
    {
        Upload::find($id)->update([
            'deleted_by' => Auth::user()->id,
        ]);
        Upload::find($id)->delete();
        return redirect('/');
    }
}
