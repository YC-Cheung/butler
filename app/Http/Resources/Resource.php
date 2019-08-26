<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public static function collection($resource)
    {
        return new ResourceCollection($resource, static::class);
    }
}
