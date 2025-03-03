<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        // Handle Validation Errors
        if ($exception instanceof ValidationException) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $exception->errors(),
            ], 422);
        }

        // Handle Authentication Errors
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        // Handle Model Not Found Errors
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not found',
            ], 404);
        }

        // Handle Route Not Found
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Endpoint not found',
            ], 404);
        }

        // Handle HTTP Errors (403, 404, 500, etc.)
        if ($exception instanceof HttpException) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());
        }

        // Default Error Response
        return response()->json([
            'message' => 'Something went wrong',
            'error' => $exception->getMessage(),
        ], 500);
    }
}
