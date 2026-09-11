<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
<<<<<<< HEAD
use Illuminate\Http\Request;
=======
>>>>>>> feature/person-1-backend

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
<<<<<<< HEAD
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
=======
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,       // jaga halaman per role (ADMIN / STAFF / USER)
            'active' => \App\Http\Middleware\EnsureUserActive::class,   // cuma yang ACTIVE boleh masuk
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
>>>>>>> feature/person-1-backend
    })->create();
