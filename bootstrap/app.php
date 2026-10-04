<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureUserActive;
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
        $middleware->alias([
            'role' => CheckRole::class,
            'active' => EnsureUserActive::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'reservasi',
            'reservasi/*',
            'petugas/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {})->create();
