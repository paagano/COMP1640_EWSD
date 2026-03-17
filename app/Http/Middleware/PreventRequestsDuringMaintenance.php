<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

// This middleware checks if the application is in maintenance mode and prevents requests from being processed if it is. 
// It allows you to specify certain URIs that should still be accessible even when the application is in maintenance mode.
class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array<int, string>
     */
    protected $except = [
        'admin/*', // Allow access to admin routes during maintenance
    ];
}
