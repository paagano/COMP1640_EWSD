<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

// The VerifyEmailController class is responsible for handling email verification for authenticated users. 
// It provides a method to mark the user's email address as verified when they click on the verification link sent to their email. 
// The __invoke method checks if the user's email is already verified. If it is, the user is redirected to the home page with a query parameter indicating that the email is verified. 
// If the email is not verified, the method attempts to mark the email as verified and triggers the Verified event if successful. 
// Finally, the user is redirected to the home page with a query parameter indicating that the email is verified. 
// This controller ensures that users can verify their email addresses securely and that the application can respond appropriately based on the verification status of the user's email.
class VerifyEmailController extends Controller
{

    // Mark the authenticated user's email address as verified.
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
}
