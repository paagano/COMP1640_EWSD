<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// This middleware checks if the user is authenticated and redirects them to the home page if they are. 
// It is typically used to prevent authenticated users from accessing the login or registration pages. 
// If the user is not authenticated, it allows the request to proceed to the intended destination. Can specify which guards to check for authentication by passing them as parameters to the middleware.
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // Handle an incoming request.
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
