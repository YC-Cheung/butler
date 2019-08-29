<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    protected $showFields = [];
    protected $hideFields = [];

    public static function collection($resource)
    {
        return new ResourceCollection($resource, static::class);
    }

    public function toArray($request)
    {
        if (is_null($this->resource)) {
            return [];
        }

        $array = is_array($this->resource)
            ? $this->resource
            : $this->resource->toArray();

        return $this->filterFields($array);
    }

    public function show(array $fields)
    {
        $this->showFields = $fields;

        return $this;
    }

    public function hide(array $fields)
    {
        $this->hideFields = $fields;

        return $this;
    }

    protected function filterFields($array)
    {
        $showFields = $this->showFields;
        $hideFields = $this->hideFields;

        return collect($array)
            ->when(!empty($showFields), function ($collection) use ($showFields) {
                return $collection->only($showFields);
            })
            ->when(!empty($hideFields), function ($collection) use ($hideFields) {
                return $collection->deepExcept($hideFields);
            })
            ->toArray();
    }
}
