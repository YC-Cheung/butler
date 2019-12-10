<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\Request;
use App\Models\Permission as Perm;

class Permission
{
    /**
     * perm check
     * allow specific permissions
     *
     * @param $permission
     * @return bool
     */
    public static function check($permission)
    {
        if (Admin::isAdministrator()) {
            return true;
        }
        if (is_array($permission)) {
            collect($permission)->each(function ($permission) {
                static::check($permission);
            });
        }
        if (Admin::user()->can($permission)) {
            return true;
        }
        return false;
    }


    /**
     * @param $roles
     * @return bool
     */
    public static function allow($roles)
    {
        if (Admin::isAdministrator()) {
            return true;
        }
        if (Admin::user()->inRoles($roles)) {
            return true;
        }
        return false;
    }

    /**
     * @param $roles
     * @return bool
     */
    public static function deny($roles)
    {
        if (Admin::isAdministrator()) {
            return false;
        }
        if (!Admin::user()->inRoles($roles)) {
            return false;
        }
        return true;
    }

    /**
     * permission route match
     * @param Permission $permission
     * @param Request $request
     * @return bool
     */
    public static function shouldPassThrough(Perm $permission, Request $request)
    {
        if (empty($permission->http_method) && empty($permission->http_path)) {
            return true;
        }

        $method = $permission->http_method;
        $matches = array_map(function ($path) use ($method) {
//            if (Str::contains($path, ':')) {
//                list($method, $path) = explode(':', $path);
//                $method = explode(',', $method);
//            }
            return compact('method', 'path');
        }, [$permission->http_path]);

        foreach ($matches as $match) {
            if (static::matchRequest($match, $request)) {
                return true;
            }
        }

        return false;
    }

    /**
     * match path method.
     *
     * @param array $match
     * @param Request $request
     *
     * @return bool
     */
    protected static function matchRequest(array $match, Request $request)
    {
        if (!$request->is(trim($match['path'], '/'))) {
            return false;
        }
        $method = collect($match['method'])->filter()->map(function ($method) {
            return strtoupper($method);
        });

        return $method->isEmpty() || $method->contains($request->method());
    }
}
