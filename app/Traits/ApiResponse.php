<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    public static function success(string $message = 'Success', mixed $data = null, int $status = 200): JsonResponse
    {

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function error(string $message = 'Something went wrong', int $status = 400, mixed $errors = null): JsonResponse
    {

        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    public static function pagination(LengthAwarePaginator $pagination, string $message = 'Data fetched successfully'): JsonResponse
    {

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $pagination->items(),
            'pagination' => [
                'current_page' => $pagination->currentPage(),
                'last_page' => $pagination->lastPage(),
                'per_page' => $pagination->perPage(),
                'total' => $pagination->total(),
                'from' => $pagination->firstItem(),
                'to' => $pagination->lastItem(),
                'has_more_pages' => $pagination->hasMorePages(),
                'next_page_url' => $pagination->nextPageUrl(),
                'prev_page_url' => $pagination->previousPageUrl(),
            ]
        ]);
    }
}
