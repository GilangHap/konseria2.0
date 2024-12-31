<?php

namespace App\Http;

use Spatie\Permission\Middlewares\RoleMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Spatie\Permission\Middlewares\PermissionMiddleware;

class Kernel extends HttpKernel
{

    protected $middleware = [
        
    ];

    protected $middlewareGroups = [
        'web' => [
            // ... other middleware
        ],

        'api' => [
            // ... other middleware
        ],
    ];

    protected $routeMiddleware = [
        // ... other middleware

    ];
}
?>
