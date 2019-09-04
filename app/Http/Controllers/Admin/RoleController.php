<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $data = RoleResource::collection(Role::with('permissions')->paginate())->withIds(['permissions']);

        return $this->success($data);
    }

    public function store(RoleRequest $request)
    {
        $inputs = $request->validated();
        $role = Role::create($inputs);

        return $this->created(RoleResource::make($role));
    }

    public function show(Role $role)
    {
        return RoleResource::make($role);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $inputs = $request->validated();
        $role->update($inputs);

        if (isset($inputs['permissions'])) {
            $role->permissions()->sync($inputs['permissions']);
        }

        return $this->created(RoleResource::make($role));
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return $this->noContent();
    }

    /**
     * 获取所有角色选项
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOption()
    {
        $data = RoleResource::collection(Role::all())->hide(['created_at', 'updated_at']);

        return $this->success($data);
    }
}
