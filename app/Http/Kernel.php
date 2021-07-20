<?php

namespace Manivelle\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        \Manivelle\Http\Middleware\Proxy::class,
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];
    
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Manivelle\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Manivelle\Http\Middleware\VerifyCsrfToken::class,
        ],
        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Manivelle\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.role' => \Bican\Roles\Middleware\VerifyRole::class,
        'auth.organisation' => \Manivelle\Http\Middleware\AuthenticateOrganisation::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'guest' => \Manivelle\Http\Middleware\RedirectIfAuthenticated::class,
        'organisation' => \Manivelle\Http\Middleware\Organisation::class,
        'screen' => \Manivelle\Http\Middleware\Screen::class,
    ];
}
