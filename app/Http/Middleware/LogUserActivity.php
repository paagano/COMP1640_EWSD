<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class LogUserActivity
{
    
    // Handle an incoming request.
    public function handle(Request $request, Closure $next): Response
    {

        $response = $next($request);

        if (auth()->check()) {

            $userAgent = $request->header('User-Agent'); // Get the User-Agent string from the request header

            $browser = $this->detectBrowser($userAgent);
            $platform = $this->detectPlatform($userAgent);

            // Skip static assets (css/js/images). We only want to log actual page visits.
            if (
                !$request->is('css/*') &&
                !$request->is('js/*') &&
                !$request->is('images/*') &&
                !$request->is('favicon.ico')
            ) {

            // Insert a new record into the activity_logs table with the collected information
                DB::table('activity_logs')->insert([
                    'user_id'    => auth()->id(),
                    'page'       => $request->path(),
                    'method'     => $request->method(),
                    'browser'    => $browser,
                    'platform'   => $platform,
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        return $response;
    }

    // ---------------------------------------------------------------------------------------
    //  Browser Detection: A simple method to identify the browser from the User-Agent string.
    // ---------------------------------------------------------------------------------------

    private function detectBrowser($userAgent)
    {

        if (str_contains($userAgent, 'Edg')) {
            return 'Edge';
        }

        if (str_contains($userAgent, 'Brave')) {
            return 'Brave';
        }

        if (str_contains($userAgent, 'OPR') || str_contains($userAgent, 'Opera')) {
            return 'Opera';
        }

        if (str_contains($userAgent, 'Firefox')) {
            return 'Firefox';
        }

        if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edg')) {
            return 'Chrome';
        }

        if (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) {
            return 'Safari';
        }

        return 'Other';
    }

    // ------------------------------------------------------------------------------------------------
    //  Platform Detection: A simple method to identify the operating system from the User-Agent string.
    // ------------------------------------------------------------------------------------------------

    private function detectPlatform($userAgent)
    {

        if (str_contains($userAgent, 'Windows')) {
            return 'Windows';
        }

        if (str_contains($userAgent, 'Mac')) {
            return 'Mac';
        }

        if (str_contains($userAgent, 'Linux')) {
            return 'Linux';
        }

        if (str_contains($userAgent, 'Android')) {
            return 'Android';
        }

        if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
            return 'iOS';
        }

        return 'Other';
    }
}