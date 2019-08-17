<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class RoleRequest extends FormRequest
{
    public function rules()
    {
        $id = (int)optional($this->route('role'))->id;
        $rules = [
            'name' => 'required|unique:admin_roles,name,' . $id,
            'slug' => 'required|unique:admin_roles,slug,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'exists:admin_permissions,id',
        ];

        if ($this->isMethod('patch')) {
            $rules = Arr::only($rules, $this->keys());
        }

        return $rules;
    }
}
