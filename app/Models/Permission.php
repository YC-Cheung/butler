<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'admin_permissions';

    protected $fillable = ['name', 'slug', 'http_method', 'http_path'];

    public static $httpMethods = [
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD',
    ];

    public function roles()
    {
        return $this->belongsToMany('admin_role_permission', 'permission_id', 'role_id');
    }

    public function getHttpPathAttribute($path)
    {
        return str_replace("\r\n", "\n", $path);
    }

    public function setHttpPathAttribute($httpPath)
    {
        if (is_array($httpPath)) {
            $this->attributes['http_path'] = implode("\n", $httpPath) ?: null;
        } else {
            $this->attributes['http_path'] = $httpPath;
        }
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
}
