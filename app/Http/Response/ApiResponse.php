<?php

namespace App\Http\Response;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

class ApiResponse implements Responsable
{
    protected int $httpCode;
    protected $data;
    protected string $errorMessage;

    public function __construct(int $httpCode, $data = null, string $errorMessage = '')
    {
        $this->httpCode = $httpCode;
        $this->data = $data;
        $this->errorMessage = $errorMessage;
    }

    public function toResponse($request): JsonResponse
    {
        $payload = match (true) {
            $this->httpCode >= 500 => [
                'error_message' => 'Server error',
                'error' => $this->data
            ],
            $this->httpCode >= 400 => [
                'error_message' => $this->errorMessage,
                'error' => $this->data,
            ],
            $this->httpCode >= 200 => $this->data,
            default => [],
        };

        return response()->json(
            [
                'status_code' => $this->httpCode,
                'data' => $payload,
            ],
            $this->httpCode
        );
    }
}
