<?php

namespace App\Models;

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use Illuminate\Database\Eloquent\Model;

class Shout extends Model
{
    protected $fillable = [
        'comment','user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id','shouts');
    }

    public function getShout()
    {
        $bbcode = new Bbcode();
        $linkify = new Linkify();

        return $bbcode->parse($linkify->linky($this->comment), true);
    }
}
