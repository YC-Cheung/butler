<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
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
    public function setStatusCode(int $statusCode): ApiResponse
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param array $data
     * @param array $headers
     * @return JsonResponse
     */
    public function respond(array $data, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $this->statusCode, $headers);
    }


    /**
     * @param $status
     * @param array $data
     * @param int|null $code
     * @return JsonResponse
     */
    public function status($status, array $data, ?int $code = null): JsonResponse
    {
        if ($code) {
            $this->setStatusCode($code);
        }

        $status = [
            'status' => $status,
            'code' => $this->statusCode
        ];

        $data = array_merge($status, $data);

        return $this->respond($data);
    }

    /**
     * @param null|string $message
     * @param int $code
     * @param string $status
     * @return JsonResponse
     */
    public function failed($message, $code = FoundationResponse::HTTP_BAD_REQUEST, $status = 'error')
    {
        return $this->setStatusCode($code)->message($message, $status);
    }

    /**
     * @param string $message
     * @param null|string $status
     * @return JsonResponse
     */
    public function message(string $message, ?string $status = 'success'): JsonResponse
    {
        return $this->status($status, [
            'message' => $message
        ]);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function internalError($message = 'Internal Error!')
    {

        return $this->failed($message, FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public function created($message = 'created')
    {
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)
            ->message($message);
    }

    /**
     * @param $data
     * @param string $status
     * @return JsonResponse
     */
    public function success($data, $status = 'success')
    {
        return $this->status($status, compact('data'));
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
