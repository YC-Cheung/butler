<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        return RoleResource::collection(Role::paginate());
    }

    public function store(RoleRequest $request)
    {
        $inputs = $request->validated();
        Role::create($inputs);

        return $this->message('角色创建成功');
    }

    public function show(Role $role)
    {
        return RoleResource::make($role);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $inputs = $request->validated();
        $role->update($inputs);

        return $this->message('角色更新成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
