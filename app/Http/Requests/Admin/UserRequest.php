<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class UserRequest extends FormRequest
{
    public function rules()
    {
        $user = $this->route('user');
        $id = (int)optional($user)->id;

        $rules = [
            'username' => 'required|max:100|unique:admin_users,username,' . $id,
            'name' => 'required|max:100',
            'avatar' => 'nullable|string|max:255',
            'password' => 'required|between:6,20|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:admin_roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:admin_permissions,id',
        ];

        if ($this->isMethod('patch')) {
            $rules = Arr::only($rules, $this->keys());
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            if (!$this->post('password') && isset($rules['password'])) {
                unset($rules['password']);
            }

            if (isset($rules['avatar']) && $this->input('avatar')) {
                if ($this->input('avatar') === Storage::disk('uploads')->url($user->avatar)) {
                    unset($rules['avatar']);
                }
            }
        }

        return $rules;
    }
}
