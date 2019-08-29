<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    protected $showFields = [];
    protected $hideFields = [];
    protected $idsRelationship = [];
    protected $idsSuffix = '_ids';
    protected $idKey = 'id';

    public static function collection($resource)
    {
        return new ResourceCollection($resource, static::class);
    }

    public function toArray($request)
    {
        if (is_null($this->resource)) {
            return [];
        }

        $idsRelationship = $this->getIdsRelationship();

        $array = is_array($this->resource)
            ? $this->resource
            : $this->resource->toArray();

        foreach ($idsRelationship as $item) {
            if (!empty($item)) {
                $array[$item . $this->idsSuffix] = $this->resource->{$item}->pluck($this->idKey);
            }
        }

        return $this->filterFields($array);
    }

    public function getIdsRelationship()
    {
        if (empty($this->idsRelationship)) {
            return [];
        }
        $resource = $this->resource;
        $keys = array_map(function ($relationship) use ($resource) {
            if ($resource->relationLoaded($relationship)) {
                return $relationship;
            } else {
                return null;
            }
        }, $this->idsRelationship);

        return $keys;
    }

    public function isNeedIds($relationship)
    {
        return in_array($relationship, $this->getIdsRelationship());
    }

    public function whenIds($relationship)
    {
        return $this->when($this->isNeedIds($relationship), $this->resource->{$relationship}->pluck($this->idKey));
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

    public function withIds(array $relationships)
    {
        $this->idsRelationship = $relationships;

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
