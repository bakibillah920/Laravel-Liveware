<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImdbKey extends Model
{
    protected $fillable = [
        'upload_id','key'
    ];
    protected $table = 'imdb_keys';

    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}
