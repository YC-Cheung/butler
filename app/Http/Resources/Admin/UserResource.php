<?php

namespace App\Http\Resources\Admin;


use App\Http\Resources\Resource;

class UserResource extends Resource
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
            'username' => $this->username,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at
        ]);
    }
}
