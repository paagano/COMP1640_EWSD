<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

// This middleware is responsible for trimming whitespace from the beginning and end of string inputs in HTTP requests. 
// It also allows you to specify certain attributes that should not be trimmed, such as passwords, to prevent unintended consequences. 
// By default, it excludes 'current_password', 'password', and 'password_confirmation' from being trimmed.
class TrimStrings extends Middleware
{
    /**
     * The names of the attributes that should not be trimmed.
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
