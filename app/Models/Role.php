<?php

namespace App\Models;

class Role extends Model
{
    protected $table = 'admin_roles';

    protected $fillable = ['name', 'slug'];

    public function administrators()
    {
        return $this->belongsToMany(Administrator::class, 'admin_role_users', 'role_id', 'user_id')->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'admin_role_permissions', 'role_id', 'permission_id')->withTimestamps();
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'admin_role_menu', 'role_id', 'menu_id')->withTimestamps();
    }
}
