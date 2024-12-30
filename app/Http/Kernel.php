<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // ... existing middlewares ...
        'role' => \App\Http\Middleware\CheckRole::class,
        'checkRole' => \App\Http\Middleware\CheckRole::class,
    ];
} 