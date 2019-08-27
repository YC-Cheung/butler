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
        $data = UserResource::collection(Administrator::paginate());

        return $this->success($data);
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

        return $this->created(UserResource::make($user));
    }

    public function show(Administrator $user)
    {
        return $this->success(UserResource::make($user));
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

        return $this->created(UserResource::make($user));
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
