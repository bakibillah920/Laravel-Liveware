<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id','name','slug','icon','serial','status'
    ];
    protected $table = 'categories';
    public function childCategories()
    {
        return $this->hasMany(Category::class, 'parent_id','id');
    }
    public function parentCategory()
    {
//        return $this->belongsTo(Category::class, 'parent_id');
        return $this->belongsTo(Category::class, 'parent_id','id','categories');
    }
    public function uploads()
    {
        return $this->hasMany(Upload::class, 'category_id');
    }
}
