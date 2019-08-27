<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param array|null $data
     * @param array $headers
     * @return JsonResponse
     */
    public function respond(?array $data, array $headers = [])
    {
        if ($data && !isset($data['data'])) {
            $data = ['data' => $data];
        }
        return new JsonResponse($data, $this->statusCode, $headers);
    }


    /**
     * @param null $data
     * @param array $headers
     * @return JsonResponse
     */
    public function success($data = null, $headers = [])
    {
        if ($data instanceof JsonResource) {
            return $data
                ->response()
                ->withHeaders($headers)
                ->setStatusCode(FoundationResponse::HTTP_OK);
        }

        return $this->respond($data, $headers);
    }

    /**
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    public function failed($message, $code = FoundationResponse::HTTP_BAD_REQUEST)
    {
        $data = ['message' => $message];

        return $this->setStatusCode($code)->respond($data);
    }

    /**
     * @param null $data
     * @param array $headers
     * @return JsonResponse
     */
    public function created($data = null, array $headers = [])
    {
        if ($data instanceof JsonResource) {
            return $data
                ->response()
                ->withHeaders($headers)
                ->setStatusCode(FoundationResponse::HTTP_CREATED);
        }
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)->respond($data, $headers);
    }

    /**
     * @return JsonResponse
     */
    public function noContent()
    {
        return $this->setStatusCode(FoundationResponse::HTTP_NO_CONTENT)->respond(null);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function notFound($message = 'Not Found!')
    {
        return $this->failed($message, Foundationresponse::HTTP_NOT_FOUND);
    }
}
