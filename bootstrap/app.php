<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Modules\Auth\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Custom Exception 1: InsufficientStockException (Modul Products)
        $exceptions->render(function (\Modules\Products\Exceptions\InsufficientStockException $e, Request $request) {
            return $e->render($request);
        });

        // Custom Exception 2: BookNotAvailableException (Studi Kasus Perpustakaan)
        $exceptions->render(function (\Modules\Library\Exceptions\BookNotAvailableException $e, Request $request) {
            return $e->render($request);
        });

        // Custom Exception 3: AuthorHasBooksException (Studi Kasus Perpustakaan)
        $exceptions->render(function (\Modules\Library\Exceptions\AuthorHasBooksException $e, Request $request) {
            return $e->render($request);
        });
    })->create();
