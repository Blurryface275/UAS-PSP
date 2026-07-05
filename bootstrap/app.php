<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // middleware yg udah dibuat ditaruh di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // OWASP A10:2025 Mishandling of Exceptional Conditions
        // Pastikan tidak ada kebocoran kredensial database (Stack Trace) ke pengguna umum
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            if (!config('app.debug')) {
                return response()->json([
                    'error' => 'Layanan Sedang Terganggu',
                    'message' => 'Koneksi ke pangkalan data gagal. Tim kami sedang menanganinya.'
                ], 500);
            }
        });
    })->create();
