<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ImdbKey;
use App\Models\ImdbDetail;
use App\Models\Recommend;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class LandingController extends Controller
{

    public function index()
    {
     
        $data['pinned'] = Upload::join('approvals','uploads.id','approvals.upload_id')->where('approvals.is_approved','=','Approved')
            ->join('pins','uploads.id','pins.upload_id')
            ->leftJoin('imdb_details', 'uploads.id', '=', 'imdb_details.upload_id')
//            ->select('uploads.*')
            ->select('uploads.id','uploads.category_id','uploads.name','uploads.slug',
            'uploads.image','uploads.created_at','imdb_details.rating')
            ->latest()
            ->get();
        $data['recommended'] = Upload::join('approvals','uploads.id','approvals.upload_id')
            ->where('approvals.is_approved','=','Approved')
            ->join('recommends','uploads.id','recommends.upload_id')
            ->leftJoin('imdb_details', 'uploads.id', '=', 'imdb_details.upload_id')
            ->inRandomOrder()
//            ->select('uploads.*')
            ->select('uploads.id','uploads.category_id','uploads.name','uploads.slug',
            'uploads.image','uploads.created_by','imdb_details.rating')
            ->get();
        $hiddenCategories = Category::where('name', 'Premium')->first();

        $hiddenIDS = [];

        foreach ($hiddenCategories->childCategories as $hiddenCategory)
        {
            array_push($hiddenIDS, $hiddenCategory->id);
        }

        $data['uploads'] = Upload::join('categories', 'uploads.category_id', 'categories.id')
            ->whereNotIn('categories.id', $hiddenIDS)
            ->join('approvals','uploads.id','approvals.upload_id')
            ->leftJoin('imdb_details', 'uploads.id', '=', 'imdb_details.upload_id')
            ->where('approvals.is_approved','=','Approved')
//            ->select('uploads.*')
            ->select('uploads.id','uploads.category_id','uploads.is_anonymous',
            'uploads.name','uploads.slug','uploads.image','uploads.torrent',
            'uploads.created_at','uploads.created_by','imdb_details.rating')
            ->latest()
            ->paginate(25);
        return view('app.landing.index', $data);
    }

    public function categories($category)
    {
        $category = Category::where('slug', '=', $category)->first();

        $data['category'] = Category::find($category->id);

        $subcategories = Category::where(['parent_id' => $category->id])->pluck('id');

//        dd($subcategories);

        $subcategory = [];

        foreach ($subcategories as $subcat)
        {
            array_push($subcategory, $subcat);
        }

        $upload = $data['uploads'] = Upload::whereIn('uploads.category_id', $subcategory)
            ->join('approvals','uploads.id','approvals.upload_id')
            ->leftJoin('imdb_details', 'uploads.id', '=', 'imdb_details.upload_id')
            ->where('approvals.is_approved','=','Approved')
//            ->select('uploads.*')
            ->select('uploads.id','uploads.category_id','uploads.is_anonymous',
            'uploads.name','uploads.slug','uploads.image','uploads.torrent',
            'uploads.created_at','uploads.created_by','imdb_details.rating')
            ->latest()
            ->paginate(25);

//        $data['all_latest'] = Upload::whereIn('uploads.category_id', $subcategory)->join('approvals','uploads.id','approvals.upload_id')->where('approvals.is_approved','=','Approved')->orderBy('uploads.created_at','desc')->get();

        return view('app.landing.categories.categories', $data);
    }

    public function category_index($category, $subcategory)
    {
        $category = Category::where('slug', '=', $category)->first();
        $data['category'] = Category::find($category->id);
        $data['childCategory'] = Category::where(['slug' => $subcategory, 'parent_id' => $category->id])->first();
        $subcategory = Category::where(['slug' => $subcategory, 'parent_id' => $category->id])->first();
//        dd($subcategory);
//        $data['uploads'] = Upload::where(['category_id' => $subcategory->id])->join('approvals','uploads.id','approvals.upload_id')->where('approvals.is_approved','=','Approved')->select('uploads.*')->paginate(50);
        $data['uploads'] = Upload::where(['category_id' => $subcategory->id])
            ->join('approvals','uploads.id','approvals.upload_id')
            ->leftJoin('imdb_details', 'uploads.id', '=', 'imdb_details.upload_id')
            ->where('approvals.is_approved','=','Approved')
//            ->select('uploads.*')
            ->select('uploads.id','uploads.category_id','uploads.is_anonymous',
            'uploads.name','uploads.slug','uploads.image','uploads.torrent',
            'uploads.created_at','uploads.created_by','imdb_details.rating')
            ->latest()
            ->paginate(20);

        /*$data['recommended'] = Upload::join('approvals','uploads.id','approvals.upload_id')->where('approvals.is_approved','=','Approved')
            ->join('recommends','uploads.id','recommends.upload_id')
            ->inRandomOrder()
            ->select('uploads.*')
            ->get();*/

//        $data['all_latest'] = Upload::join('approvals','uploads.id','approvals.upload_id')->where('approvals.is_approved','=','Approved')->orderBy('uploads.created_at','desc')->get();

        return view('app.landing.categories.index', $data);
    }
    public function category_show($category, $subcategory, $torrentslug)
    {
        $category = Category::where('slug', '=', $category)->first();
        $data['category'] = Category::find($category->id);
        $data['childCategory'] = Category::where(['slug' => $subcategory, 'parent_id' => $category->id])->first();
        $subcategory = Category::where(['slug' => $subcategory, 'parent_id' => $category->id])->first();
        $data['upload'] = Upload::where(['category_id' => $subcategory->id, 'slug' => $torrentslug])->first();

        /*$data['recommended'] = Upload::join('approvals','uploads.id','approvals.upload_id')
            ->where('approvals.is_approved','=','Approved')
            ->join('recommends','uploads.id','recommends.upload_id')
            ->inRandomOrder()
            ->get();*/
//        $data['all_latest'] = Upload::orderBy('created_at','desc')->join('approvals','uploads.id','approvals.upload_id')->where('approvals.is_approved','=','Approved')->select('uploads.*')->get();

        return view('app.landing.categories.show', $data);
    }

    public function search_index(Request $request)
    {

        $data['query'] = $request->searchTxt;
        $data['results'] = Upload::where('name', 'like', '%' . $request->searchTxt . '%')
            ->join('approvals','uploads.id','approvals.upload_id')
            ->where('approvals.is_approved','=','Approved')
//            ->select('uploads.*')
            ->select('uploads.id','uploads.category_id','uploads.is_anonymous',
            'uploads.name','uploads.slug','uploads.image','uploads.torrent',
            'uploads.created_at','uploads.created_by')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('app.landing.search.index', $data);

    }

    public function imdb_detail_insert(Request $request)
    {
        
        /*$imdbKeys = ImdbKey::where('upload_id','>',781)->first();
        $IMDB = new \IMDB($imdbKeys->key);
        // echo $IMDB->getRating() ."<br>";
        ImdbDetail::create([
            'upload_id'         =>$imdbKeys->upload_id,
            'rating'            =>$IMDB->getRating(),
            'release_date'      =>$IMDB->getReleaseDate(),
            'genre'             =>$IMDB->getGenre(),
            'director'          =>$IMDB->getDirector(),
            'awards'            =>$IMDB->getAwards(),
            'description'       =>$IMDB->getDescription(),
        ]);*/
            
        $imdbDetails = ImdbDetail::select('upload_id')->pluck('upload_id');
        $imdbKeys = ImdbKey::whereNotIn('upload_id',$imdbDetails)->get();
        echo "<br>";
        echo "------------------- not found -----------------------";
        echo "<br>";
        foreach($imdbKeys as $serial => $imdbKey)
        {
            echo $imdbKey->id.": ".$imdbKey->key."<br>";
        }
        echo "------------------- not found -----------------------";
        echo "<br>";
        // dd($imdbKeys);
        /*foreach ($imdbKeys as $serial => $imdbKey)
        {
            $IMDB = new \IMDB($imdbKey->key);
            // echo $IMDB->getRating() ."<br>";
            ImdbDetail::create([
                'upload_id'         =>$imdbKey->upload_id,
                'rating'            =>$IMDB->getRating(),
                'release_date'      =>$IMDB->getReleaseDate(),
                'genre'             =>$IMDB->getGenre(),
                'director'          =>$IMDB->getDirector(),
                'awards'            =>$IMDB->getAwards(),
                'description'       =>$IMDB->getDescription(),
            ]);
        }*/
        die();

        return back()->withSuccess('done!');

    }

}
