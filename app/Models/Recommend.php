<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Recommend extends Model
{

    protected $fillable = [
        'upload_id','is_recommended','created_by',
    ];
    protected $table = 'recommends';

    public function upload()
    {
        return $this->belongsTo(Upload::class);
    }
    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public static function boot() {
        parent::boot();
        // increment serial on each insert
        static::creating(function($table)  {
            $table->created_by = Auth::id();
            $table->created_at = Carbon::now();
        });
    }
}
