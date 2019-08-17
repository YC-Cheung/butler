<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index()
    {
        return PermissionResource::collection(Permission::paginate());
    }

    public function store(PermissionRequest $request)
    {
        $inputs = $request->validated();
        Permission::create($inputs);

        return $this->message('权限创建成功');
    }

    public function show(Permission $permission)
    {
        return PermissionResource::make($permission);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $inputs = $request->validated();
        $permission->update($inputs);

        return $this->message('权限更新成功');
    }

    public function destroy($id)
    {
        //
    }
}
