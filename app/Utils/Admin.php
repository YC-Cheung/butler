<?php

namespace App\Utils;

use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * @return  \App\Models\Administrator
     */
    public static function user()
    {
        return static::guard()->user();
    }

    public static function isAdministrator()
    {
        return static::user() && static::user()->isAdministrator();
    }

    /**
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public static function guard()
    {
        return auth('admin');
    }
}
