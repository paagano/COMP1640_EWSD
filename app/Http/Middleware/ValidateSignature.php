<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

// This middleware is responsible for validating the signature of incoming requests to ensure that they have not been tampered with. 
// It checks the signature of the request against a known secret key and returns a 403 Forbidden response if the signature is invalid. 
// Can specify certain query string parameters to be ignored when validating the signature, which can be useful for parameters that are added by third-party services or analytics tools
class ValidateSignature extends Middleware
{
    /**
     * The names of the query string parameters that should be ignored.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'fbclid',
        // 'utm_campaign',
        // 'utm_content',
        // 'utm_medium',
        // 'utm_source',
        // 'utm_term',
    ];
}
