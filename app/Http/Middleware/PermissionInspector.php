<?php

namespace App\Http\Middleware;

use App\Http\Exceptions\PermissionException;
use App\Models\Permission;
use App\Utils\Admin;
use App\Utils\Permission as Checker;
use Closure;


class PermissionInspector
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws PermissionException
     */
    public function handle($request, Closure $next)
    {
        // check login status
        if (!Admin::user()) {
            throw new PermissionException();
        }
        if (!$this->checkPermission($request)) {
            throw new PermissionException();
        }

        return $next($request);
    }

    public function checkPermission($request)
    {
        $allPermissions = Admin::user()->allPermissions();
        foreach ($allPermissions as $permission) {
            if (Checker::shouldPassThrough($permission, $request)) {
                return true;
            }
        }
        return false;
    }
}
