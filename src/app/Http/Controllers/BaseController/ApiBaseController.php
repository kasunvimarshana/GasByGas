<?php

namespace App\Http\Controllers\BaseController;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\BaseController\BaseController;

abstract class ApiBaseController extends BaseController {
    /**
     * Standard success response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse(
        mixed $data = null,
        string $message = 'Operation successful',
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Standard error response.
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed|null $errors
     * @return JsonResponse
     */
    protected function errorResponse(
        string $message = 'An error occurred',
        int $statusCode = 400,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * Standard paginated response.
     *
     * @param mixed $paginator
     * @param string $message
     * @return JsonResponse
     */
    protected function paginatedResponse(
        mixed $paginator,
        string $message = 'Paginated results retrieved successfully'
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'total_pages' => $paginator->lastPage(),
                'total_items' => $paginator->total(),
                'per_page' => $paginator->perPage(),
            ],
        ]);
    }
}
