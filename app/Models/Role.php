<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'admin_roles';

    protected $fillable = ['name', 'slug'];

    public function administrators()
    {
        return $this->belongsToMany(Administrator::class, 'admin_role_user', 'role_id', 'user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_role_permission', 'role_id', 'permission_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'admin_role_menu', 'role_id', 'menu_id');
    }
}
