<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'upload_id','is_approved','disapproved_reason','created_by','updated_by'
    ];
    protected $table = 'approvals';

    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
}
