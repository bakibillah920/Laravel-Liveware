<?php

namespace App\Models;

use App\Helpers\Bbcode;
use App\Helpers\Linkify;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Upload extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'category_id','name','slug','image','torrent','description','resolution','is_anonymous','created_by','updated_by','deleted_by','delete_reason'
    ];
    protected $table = 'uploads';
    public function imdbKey()
    {
        return $this->hasOne(ImdbKey::class, 'upload_id');
    }
    public function imdbDetail()
    {
        return $this->hasOne(ImdbDetail::class, 'upload_id');
    }
    public function approval()
    {
        return $this->hasOne(Approval::class, 'upload_id');
    }
    public function pin()
    {
        return $this->hasOne(Pin::class, 'upload_id');
    }
    public function recommend()
    {
        return $this->hasOne(Recommend::class, 'upload_id');
    }
    public function created_by_user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function deleted_by_user()
    {
        return $this->belongsTo(User::class, 'deleted_by');
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
        // create a event to happen on deleting
        static::deleting(function($table)  {
            $table->deleted_by = Auth::id();
            $table->deleted_at = Carbon::now();
            if ($table->created_by != Auth::id())
            {
                Pm::create([
                    'sender_id'         => Auth::user()->id,
                    'receiver_id'       => $table->created_by,
                    'subject'           => 'Your Content "'.$table->name.'" has been Deleted',
                    'description'       => 'Your uploaded file "'.$table->name.'" has been deleted! Fore more information you can pm...',
                ]);
            }
            else
            {
                Pm::create([
                    'sender_id'         => Auth::user()->id,
                    'receiver_id'       => $table->created_by,
                    'subject'           => 'Your Content "'.$table->name.'" has been Deleted!',
                    'description'       => 'Your uploaded file "'.$table->name.'" has been Deleted by you!',
                ]);
            }
        });
    }
    public function getDescription()
    {
        $bbcode = new Bbcode();
        $linkify = new Linkify();

        return $bbcode->parse($linkify->linky($this->description), true);
    }
}
