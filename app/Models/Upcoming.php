<?php

namespace App\Models;

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Upcoming extends Model
{
    protected $fillable = [
        'category_id','name','slug','image','description','created_by','updated_by','upload_date','imdbURL'
    ];
    protected $table = 'upcomings';
    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public static function boot() {
        parent::boot();
        // increment serial on each insert
        static::creating(function($table)  {
            $table->created_by = Auth::id();
            $table->created_at = Carbon::now();
        });
        // create a event to happen on updating
        static::updating(function($table)  {
            $table->updated_by = Auth::id();
            $table->updated_at = Carbon::now();
        });
    }
    public function getDescription()
    {
        $bbcode = new Bbcode();
        $linkify = new Linkify();

        return $bbcode->parse($linkify->linky($this->description), true);
    }
}
