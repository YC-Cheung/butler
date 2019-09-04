<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Models\Permission;
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
        $permission = Permission::create($inputs);

        return $this->created(PermissionResource::make($permission));
    }

    public function show(Permission $permission)
    {
        return PermissionResource::make($permission);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $inputs = $request->validated();
        $permission->update($inputs);

        return $this->created(PermissionResource::make($permission));
    }

    public function getOption()
    {
        $data = PermissionResource::collection(Permission::all())->hide(['created_at', 'updated_at']);

        return $this->success($data);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return $this->noContent();
    }
}
