<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'username', 'email', 'password', 'role_id', 'avatar', 'last_online', 'dob', 'gender', 'status', 'status_reason'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
//        return $this->belongsTo(Role::class);
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class,'permission_user');
    }

    public function hasRole( ... $roles ) {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    protected function hasPermission($permission) {
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }

    public function hasPermissionTo($permission) {
        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }

    public function hasPermissionThroughRole($permission) {
        foreach ($permission->roles as $role){
            if($this->roles->contains($role)) {
                return true;
            }
        }
        return false;
    }

    protected function getAllPermissions(array $permissions) {
        return Permission::whereIn('slug',$permissions)->get();
    }


    public function pms()
    {
        return $this->hasMany(Pm::class,'receiver_id','id');
    }
    public function help_requester()
    {
        return $this->hasMany(Help::class,'asked_by','id');
    }
    public function help_answerer()
    {
        return $this->hasMany(Help::class,'answered_by','id');
    }
    public function uploads()
    {
        return $this->hasMany(Upload::class,'created_by','id');
    }

}
