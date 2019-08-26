<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ResourceCollection extends AnonymousResourceCollection
{
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
