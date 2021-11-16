<?php

namespace App\Models;

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'request_by','category_id','name','description','is_filled','filled_url','filled_by',
    ];
    protected $table = 'requests';
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function requester()
    {
        return $this->belongsTo(User::class,'request_by','id','requests');
    }
    public function requestFiller()
    {
        return $this->belongsTo(User::class,'filled_by','id','requests');
    }
    public function getDescription()
    {
        $bbcode = new Bbcode();
        $linkify = new Linkify();

        return $bbcode->parse($linkify->linky($this->description), true);
    }
}
