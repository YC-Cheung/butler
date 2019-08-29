<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Resource;

class PermissionResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'http_method' => $this->http_method,
            'http_path' => $this->http_path,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ]);
    }
}
