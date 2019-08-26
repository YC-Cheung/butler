<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = request(['username', 'password']);

        if (!$token = Auth::guard('admin')->attempt($credentials)) {
            return $this->failed('用户账号或密码错误', Response::HTTP_UNAUTHORIZED);
        }

        return $this->success([
            'token' => 'bearer ' . $token,
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        //
    }

    public function userInfo()
    {
        $user = Auth::user();

        return $this->success(UserResource::make($user));
    }
}
