<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('api', [
            \App\Http\Middleware\EnsureJsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            return \App\Http\Helpers\ResponseJson::error(__('messages.not_found'), 404);
        });

        $exceptions->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return \App\Http\Helpers\ResponseJson::error(__('messages.not_found'), 404);
        });

        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e) {
            return \App\Http\Helpers\ResponseJson::error(__('messages.unauthenticated'), 401);
        });

        $exceptions->renderable(function (\DomainException $e) {
            return \App\Http\Helpers\ResponseJson::error($e->getMessage(), 422);
        });
    })->create();
