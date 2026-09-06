<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponse
{
    /**
     * Return a standardized success JSON response.
     */
    public function success(mixed $data = null, string $message = 'Operation successful', int $code = Response::HTTP_OK, array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => array_merge([
                'timestamp' => now()->timestamp,
                'version' => '1.0.0',
            ], $meta),
        ], $code);
    }

    /**
     * Return a standardized error JSON response.
     */
    public function error(string $message = 'An error occurred', int $code = Response::HTTP_BAD_REQUEST, mixed $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'meta' => [
                'timestamp' => now()->timestamp,
                'version' => '1.0.0',
            ],
        ], $code);
    }
}
