<?php

namespace App\Modules\Shared\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class DomainException extends Exception
{
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
            'error' => static::errorCode(),
        ], $this->statusCode());
    }

    abstract protected static function errorCode(): string;

    protected function statusCode(): int
    {
        return 422;
    }
}
