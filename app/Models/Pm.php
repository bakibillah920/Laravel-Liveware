<?php

namespace App\Models;

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use Illuminate\Database\Eloquent\Model;

class Pm extends Model
{
    protected $fillable = [
        'replay_id','sender_id','receiver_id','subject','description','read_at',
    ];

    public function pm_replay()
    {
        return $this->hasMany(Pm::class,'replay_id','id','pms');
    }

    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id','id','pms');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class,'receiver_id','id','pms');
    }

    public function getDescription()
    {
        $bbcode = new Bbcode();
        $linkify = new Linkify();

        return $bbcode->parse($linkify->linky($this->description), true);
    }
}
