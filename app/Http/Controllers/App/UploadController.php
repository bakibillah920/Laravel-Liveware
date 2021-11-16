<?php

namespace App\Http\Controllers\App;

use App\Helpers\TorrentRW;
use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Category;
use App\Models\ImdbDetail;
use App\Models\ImdbKey;
use App\Models\Pm;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Validator, Session;
use File;

class UploadController extends Controller
{
    public function index()
    {
        $data['uploads'] = Upload::orderBy('id','desc')->paginate(25);
        return view('app.uploads.index', $data);
    }
    public function allApproved()
    {
        $data['uploads'] = Upload::join('approvals', 'approvals.upload_id', 'uploads.id')->where('approvals.is_approved', 'Approved')->orderBy('uploads.id','desc')->paginate(25);
        return view('app.uploads.approved', $data);
    }
    public function allDisapproved()
    {
        $data['uploads'] = Upload::join('approvals', 'approvals.upload_id', 'uploads.id')->where('approvals.is_approved', 'Disapproved')->orderBy('uploads.id','desc')->paginate(25);
        return view('app.uploads.disapproved', $data);
    }
    public function allPending()
    {
        $data['uploads'] = Upload::join('approvals', 'approvals.upload_id', 'uploads.id')->where('approvals.is_approved', 'Pending')->orderBy('uploads.id','desc')->paginate(25);
        return view('app.uploads.pending', $data);
    }

    public function create()
    {
//        $data['categories'] = Category::where(['parent_id' => ''])->get();
        $data['categories'] = Category::all();
        return view('app.uploads.create', $data);
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

        if ($validator->passes())
        {

            $category = Category::find($request->category);

            /*$torrentRW = new TorrentRW($request->torrent);

            foreach ($torrentRW->announce() as $announce)
            {
                array_push($announce, 'udp://tracker.top10torrent.site:2020/announce');
//                print_r($announce);
            }*/
//            die();

            $torrent    = $request->file('torrent');

            $torrentRW = new TorrentRW($torrent);
            $torrentRW->announce('udp://tracker.top10torrent.site:2020/announce');

            $torrentFile   = 'Top10Torrent.site-'.Str::slug($request->name).'.'.$torrent->getClientOriginalExtension();
//            $torrent->move(public_path('torrents/'.$category->parentCategory->slug.'/'.$category->slug.'/'), $torrentFile);

            $torrent->move(public_path('torrents/'), $torrentFile);

            $torrentRW->save(public_path('torrents/'.$torrentFile));

            $image    = $request->file('image');
            $imageFile   = 'Top10Torrent.site-'.Str::slug($request->name).'.'.$image->getClientOriginalExtension();
            /*$image->move(public_path('torrents/images/'.$category->parentCategory->slug.'/'.$category->slug.'/'), $imageFile);*/

            $image = Image::make($request->file('image')->getRealPath());
            $image->resize(250, 320);
//            $image->save(public_path('torrents/'.$category->parentCategory->slug.'/'.$category->slug.'/'.$imageFile));
            $image->save(public_path('torrents/'.$imageFile));

            $upload = Upload::create([
                'category_id'       => $request->category,
                'name'              => $request->name,
                'slug'              => Str::slug($request->name),
                'imdbURL'           => $request->imdbURL,
                'torrent'           => $torrentFile,
                'image'             => $imageFile,
                'description'       => $request->description,
                'resolution'        => $request->resolution,
                'is_anonymous'      => $request->is_anonymous,
            ]);

            if ($request->imdbURL != '')
            {
                ImdbKey::create([
                    'upload_id' => $upload->id,
                    'key'       => $request->imdbURL,
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

            $data['uploads'] = Upload::orderBy('id','desc')->paginate(25);

            Session::flash('success', $upload->name.' has been uploaded successfully!');


            return view('app.uploads.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }
    public function show($id)
    {
        $data['upload'] = Upload::find($id);
        $data['categories'] = Category::all();
        return view('app.uploads.show', $data);
    }
    public function edit($id)
    {
        $data['upload'] = Upload::find($id);
        $data['categories'] = Category::all();
        return view('app.uploads.edit', $data);
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

            $approval = Approval::where('upload_id', $upload->id)->first();

            if ($approval->is_approved != $request->is_approved){

                $approval->update([
                    'is_approved'           => $request->is_approved,
                    'disapproved_reason'    => $request->disapproved_reason,
                ]);

                if ($request->is_approved == 'Approved')
                {
                    Pm::create([
                        'sender_id'         => Auth::user()->id,
                        'receiver_id'       => $upload->created_by,
                        'subject'           => 'Content Approval Status',
                        'description'       => '<b>Your content "'.$upload->name.'" has been <span class="text-success">'.$request->is_approved.'</span></b>',
                    ]);
                }
                else
                {
                    Pm::create([
                        'sender_id'         => Auth::user()->id,
                        'receiver_id'       => $upload->created_by,
                        'subject'           => 'Content Approval Status',
                        'description'       => '<b>Your content "'.$upload->name.'" has been <span class="text-danger">'.$request->is_approved.'</span></b><br/>Reason: <br/> '.$request->disapproved_reason,
                    ]);
                }
            }

            $data['uploads'] = Upload::orderBy('id','desc')->paginate(25);

            Session::flash('success', $upload->name.' has been updated successfully!');

            return view('app.uploads.index', $data);
        }

        return response()->json(['error'=>$validator->errors()->all()]);
    }
    public function destroy(Request $request, $id)
    {

//        dd($request->redirect_path.'----'.URL::to('/'));
        $rules = [];

        $rules['delete_reason']      = 'required';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes())
        {

            $upload = Upload::find($id);

            $delete_reason = $request->delete_reason;

//            dd($delete_reason);

            Upload::find($id)->update([
                'deleted_by' => Auth::user()->id,
                'delete_reason' => $delete_reason,
            ]);

            Session::flash('success', $upload->name.' has been deleted successfully!');

            $upload->delete();

            if ($request->redirect_path == URL::to('/').'/category/'.$upload->category->parentCategory->slug.'/'.$upload->category->slug.'/'.$upload->slug){
                $return_path = redirect('/category/'.$upload->category->parentCategory->slug.'/'.$upload->category->slug);
            }
            else
            {
                $return_path = redirect()->back();
            }

            return $return_path;

        }

//        return back()->withErrors(['error'=>$validator->errors()->all()]);

        if($request->ajax())
        {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        return back()->withErrors(['error'=>$validator->errors()->all()]);

    }

    public function destroyedFiles()
    {

        // Through forbidden error if user doesn't has permission
        if (Auth::user()->cannot('manage-upload-delete-history')){
            return abort(403);
        }

        $data['uploads'] = Upload::onlyTrashed()->orderBy('id','desc')->paginate(25);
        return view('app.uploads.delete', $data);
    }

    public function restoreSingleDestroyedFile($id)
    {

        // Through forbidden error if user doesn't has permission
        if (Auth::user()->cannot('manage-upload-delete-history')){
            return abort(403);
        }

        $toDelete = Upload::onlyTrashed()->find($id);

        $torrentFile = $toDelete->torrent;
        $imageFile = $toDelete->image;

        // Force deleting a single model instance...
        $toDelete->restore();

        return  back();
    }

    public function clearSingleDestroyedFile($id)
    {

        // Through forbidden error if user doesn't has permission
        if (Auth::user()->cannot('manage-upload-delete-history')){
            return abort(403);
        }

        $toDelete = Upload::onlyTrashed()->find($id);

        $torrentFile = $toDelete->torrent;
        $imageFile = $toDelete->image;

        // Deleting torrent from public storage
        if(\File::exists(public_path('torrents/'.$torrentFile))){
            \File::delete(public_path('torrents/'.$torrentFile));
        }

        // Deleting image from public storage

        if(\File::exists(public_path('torrents/'.$imageFile))){
            \File::delete(public_path('torrents/'.$imageFile));
        }

        // Force deleting a single model instance...
        $toDelete->forceDelete();

        return  back();
    }

    public function clearAllDestroyedFiles()
    {

        // Through forbidden error if user doesn't has permission
        if (Auth::user()->cannot('manage-upload-delete-history')){
            return abort(403);
        }

        $toDeleteMultiple = Upload::onlyTrashed()->get();

        foreach ($toDeleteMultiple as $toDeleteSingle)
        {

            // Force deleting a single model instance...
            $toDeleteSingle->forceDelete();

            $torrentFile = $toDeleteSingle->torrent;
            $imageFile = $toDeleteSingle->image;

            if(\File::exists(public_path('torrents/'.$torrentFile))){
                \File::delete(public_path('torrents/'.$torrentFile));
            }

            if(\File::exists(public_path('torrents/'.$imageFile))){
                \File::delete(public_path('torrents/'.$imageFile));
            }
        }
;
        return  back();
    }
}
