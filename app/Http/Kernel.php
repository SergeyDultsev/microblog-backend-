<?php


namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Auth\Middleware\Authenticate;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Middleware\CorsAndHeaderMiddleware;
use App\Http\Middleware\AdminMiddleware;

class Kernel extends HttpKernel
{
    protected $middleware = [
        VerifyCsrfToken::class,
        CorsAndHeaderMiddleware::class,
    ];

    protected $middlewareGroups = [
        'api' => [
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            SubstituteBindings::class,
            ThrottleRequests::class . ':api',
        ],
    ];

    protected $routeMiddleware = [
        'admin' => AdminMiddleware::class,
        'auth' => Authenticate::class,
    ];
}

