<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\UserResource;
use App\Models\Menu;
use App\Models\Permission;
use App\Utils\Admin;
use App\Utils\CommonUtils;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
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

    public function userInfo(Menu $menu)
    {
        $user = Admin::user()->load('roles');

        if ($user->isAdministrator()) {
            $perms = Permission::all()->pluck('slug');
        } else {
            $perms = $user->allPermissionSlug();
        }

        $menuTree = $menu->treeWithAuth()->toTree();
        $data = [
            'id' => $user['id'],
            'name' => $user['name'],
            'username' => $user['name'],
            'avatar' => $user['avatar'],
            'introduction' => 'what!',
            'roles' => Arr::pluck($user['roles'], 'slug'),
            'perms' => $perms,
            'menu' => CommonUtils::menuToVue($menuTree)
        ];

        return $this->success($data);
    }
}
