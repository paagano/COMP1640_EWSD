<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

// This middleware is responsible for verifying the CSRF token in incoming requests to protect against cross-site request forgery attacks. 
// It checks the token in the request against the token stored in the user's session and returns a 419 Page Expired response if the tokens do not match. 
// Can specify certain URIs that should be excluded from CSRF verification, which can be useful for APIs or webhooks that do not use sessions or for endpoints that need to be accessed by third-party services.
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'api/*', // Exclude API routes from CSRF verification
    ];
}
