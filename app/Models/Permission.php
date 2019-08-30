<?php

namespace App\Models;

class Permission extends Model
{
    protected $table = 'admin_permissions';

    protected $fillable = ['name', 'slug', 'http_method', 'http_path'];

    public static $httpMethods = [
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD',
    ];

    public function roles()
    {
        return $this->belongsToMany(Permission::class, 'admin_role_permissions', 'permission_id', 'role_id')->withTimestamps();
    }

    public function setHttpMethodAttribute($method)
    {
        if (is_array($method)) {
            $this->attributes['http_method'] = implode(',', $method) ?: null;
        } else {
            $this->attributes['http_method'] = $method;
        }
    }

    public function getHttpMethodAttribute($method)
    {
        if (is_string($method)) {
            return array_filter(explode(',', $method));
        }

        return $method;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($permission) {
            $permission->roles()->detach();
        });
    }
}
