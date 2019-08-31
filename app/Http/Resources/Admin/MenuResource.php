<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Resource;

class MenuResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'name' => $this->name,
            'title' => $this->title,
            'path' => $this->path,
            'component' => $this->component,
            'roles' => $this->whenIds('roles', true)
        ]);
    }
}
