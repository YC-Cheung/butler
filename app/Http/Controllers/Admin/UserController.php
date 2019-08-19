<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Administrator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(Administrator::paginate());
    }

    public function store(UserRequest $request)
    {
        $inputs = $request->validated();
        $inputs['password'] = bcrypt($inputs['password']);

        $user = Administrator::create($inputs);

        if (!empty($roles = $request->post('roles', []))) {
            $user->roles()->attach($roles);
        }

        if (!empty($permissions = $request->post('permissions', []))) {
            $user->permissions()->attch($permissions);
        }

        return $this->message('用户创建成功');
    }

    public function show(Administrator $user)
    {
        return UserResource::make($user);
    }

    public function update(UserRequest $request, Administrator $user)
    {
        $inputs = $request->validated();
        $user->update($inputs);

        if (isset($inputs['roles'])) {
            $user->roles()->sync($inputs['roles']);
        }
        if (isset($inputs['permissions'])) {
            $user->permissions()->sync($inputs['permissions']);
        }

        return $this->message('用户更新成功');
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
