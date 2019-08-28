<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class Resource extends JsonResource
{
    protected $withoutFields = [];

    protected $timestampFields = ['created_at', 'updated_at', 'xxx_at'];

    public static function collection($resource)
    {
        return new ResourceCollection($resource, static::class);
    }

    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function hide(array $fields)
    {
        $this->withoutFields = $fields;

        return $this;
    }

    public function withResponse($request, $response)
    {
        $data = $response->getData(true);

        if (!empty($this->withoutFields)) {
            $wrap = self::$wrap;
            if ($wrap) {
                $collection = new Collection($data[$wrap]);
                $data[$wrap] = $collection->deepForget($this->withoutFields);
            }
        }

        $response->setData($data);
    }
}
