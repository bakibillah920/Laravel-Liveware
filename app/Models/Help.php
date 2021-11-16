<?php

namespace App\Models;

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    protected $fillable = [
        'asked_by','answered_by','is_answered','subject','question','answer','read_at',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class,'asked_by','id','helps');
    }

    public function answerer()
    {
        return $this->belongsTo(User::class,'answered_by','id','helps');
    }

    public function getQuestion()
    {
        $bbcode = new Bbcode();
        $linkify = new Linkify();

        return $bbcode->parse($linkify->linky($this->question), true);
    }

    public function getAnswer()
    {
        $bbcode = new Bbcode();
        $linkify = new Linkify();

        return $bbcode->parse($linkify->linky($this->answer), true);
    }
}
