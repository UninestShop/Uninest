<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    // ...existing code...

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        // ...existing code...

        $this->routes(function () {
            // ...existing routes...
        });
        
        // Remove this line - middleware is already registered in Kernel.php
        // $this->app['router']->pushMiddlewareToGroup('web', \App\Http\Middleware\EnsureProfileIsComplete::class);
    }
}