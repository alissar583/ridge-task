<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
          $exceptions->render(function (Throwable $exception, Request $request) {
            if (! $request->expectsJson()) {
                return null; 
            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => $exception->errors(),
                ], 422);
            }

            if ($exception instanceof AuthorizationException) {
                return response()->json([
                    'message' => 'You are not authorized to perform this action.',
                ], 403);
            }

            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            if ($exception instanceof ModelNotFoundException) {
                $modelName = class_basename($exception->getModel());
                return response()->json([
                    'message' => "{$modelName} not found.",
                ], 404);
            }

            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => 'Route not found.',
                ], 404);
            }

            if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'message' => 'HTTP method not allowed.',
                ], 405);
            }

            return response()->json([
                'message' => 'An unexpected error occurred.',
                'error' => config('app.debug') ? $exception->getMessage() : null,
            ], 500);
        });
    
    })->create();
