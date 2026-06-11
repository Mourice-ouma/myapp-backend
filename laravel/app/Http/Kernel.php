<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected $middleware = [
        \App\Http\Middleware\HandleCors::class,
    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [],
        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware aliases.
     */
    protected $middlewareAliases = [
        'auth'         => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic'   => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings'     => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers'=> \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'          => \Illuminate\Auth\Middleware\Authorize::class,
        'throttle'     => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'role'         => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
