<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

// This middleware is responsible for defining which hosts should be trusted by the application. 
// It typically allows all subdomains of the application URL to be trusted, which is useful for applications that need to handle requests from multiple subdomains. 
class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
