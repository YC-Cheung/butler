<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class MenuRequest extends FormRequest
{
    public function rules()
    {
        $id = (int)optional($this->route('menu'))->id;

        $rules = [
            'name' => 'required|unique:admin_menu,name,' . $id,
            'title' => 'required|max:50',
            'icon' => 'max:50',
            'path' => 'max:50',
            'component' => 'max:50',
            'order' => 'integer|between:-9999,9999',
            'roles' => 'array',
            'roles.*' => 'exists:admin_roles,id',
            'permission' => 'nullable|exists:admin_permissions,slug',
            'parent_id' => 'exists:admin_menu,id',
        ];

        if ($this->isMethod('patch')) {
            $rules = Arr::only($rules, $this->keys());
        }

        if ($this->post('parent_id') == 0) {
            $rules['parent_id'] = 'nullable';
        }

        return $rules;
    }
}
