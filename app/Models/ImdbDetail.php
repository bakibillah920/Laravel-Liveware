<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImdbDetail extends Model
{
    protected $fillable = [
        'upload_id','rating','release_date','genre','director','awards','description',
    ];
    protected $table = 'imdb_details';
}
