<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

// The Handler class is responsible for handling exceptions that occur within the application. 
// It extends the base ExceptionHandler provided by Laravel and allows for customizing how exceptions are reported and rendered. 
// In this implementation, the Handler class specifies a list of input fields that should not be flashed to the session on validation exceptions, such as 'current_password', 'password', and 'password_confirmation'. 
// This helps to prevent sensitive information from being exposed in error messages or logs. 
// The register method is used to define any custom exception handling logic, such as reporting or rendering specific types of exceptions, but in this case, it is left empty for future customization if needed.
class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    // Register the exception handling callbacks for the application.
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // This is where I can define custom logic for reporting exceptions, such as logging them to a specific channel or sending notifications.
        });
    }
}
