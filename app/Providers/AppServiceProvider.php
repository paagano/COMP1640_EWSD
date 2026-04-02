<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Artisan;

class AppServiceProvider extends ServiceProvider
{
     // Register any application services.
    public function register(): void
    {
        //
    }

     // Bootstrap any application services.
     // Ensure storage link exists. This is important for local file uploads to work correctly.
    public function boot(): void // Ensure storage link exists
    {
        if (!file_exists(public_path('storage'))) {
        try {
            Artisan::call('storage:link');
        } catch (\Exception $e) {
                // silently fail
        }
    }
    }
}
