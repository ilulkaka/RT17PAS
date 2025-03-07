<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class UserModel extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'tb_user';
    protected $primaryKey = "id";

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'user_name', 'password', 'departemen','line_process', 'level_user', 'nik',
    ];

    public function roles()
    {
        return $this->belongsToMany(RoleModel::class);
    }

    public function department()
    {
        return $this->belongsToMany(DeptModel::class);
    }

    public function hasPermissionTo($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }
 


}
