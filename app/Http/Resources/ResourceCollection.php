<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ResourceCollection extends AnonymousResourceCollection
{
    protected $withoutFields = [];

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

        unset($data['links']);
        unset($data['meta']['path']);
        unset($data['meta']['from']);
        unset($data['meta']['to']);
        unset($data['meta']['last_page']);
        $response->setData($data);
    }
}
