<?php

namespace App\Http\Requests\Admin;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{

    public function rules()
    {
        $id = (int)optional($this->route('permission'))->id;

        $rules = [
            'name' => 'required|unique:admin_permissions,name,' . $id,
            'slug' => 'required|unique:admin_permissions,slug,' . $id,
            'http_method' => 'nullable|array',
            'http_method.*' => Rule::in(Permission::$httpMethods),
            'http_path' => 'required',
        ];

        if ($this->isMethod('patch')) {
            $rules = Arr::only($rules, $this->keys());
        }

        return $rules;
    }
}
