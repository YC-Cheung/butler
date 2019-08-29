<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ResourceCollection extends AnonymousResourceCollection
{
    protected $showFields = [];
    protected $hideFields = [];

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

    public function toArray($request)
    {
        return $this->processCollection($request);
    }

    protected function processCollection($request)
    {
        return $this->collection->map(function (Resource $resource) use ($request) {
            return $resource->show($this->showFields)->hide($this->hideFields)->toArray($request);
        })->all();
    }

    public function withResponse($request, $response)
    {
        $data = $response->getData(true);
        unset($data['links']);
        unset($data['meta']['path']);
        unset($data['meta']['from']);
        unset($data['meta']['to']);
        unset($data['meta']['last_page']);
        $response->setData($data);
    }
}
