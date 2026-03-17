<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

// The EmailVerificationPromptController class is responsible for displaying the email verification prompt to users who have not yet verified their email addresses. 
// It checks if the user's email is already verified. If it is, the user is redirected to the home page. If not, the user is presented with the email verification prompt view. 
// This controller ensures that users are prompted to verify their email addresses before accessing certain parts of the application, enhancing security and ensuring that users have provided valid contact information.
class EmailVerificationPromptController extends Controller
{

    // Display the email verification prompt.
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(RouteServiceProvider::HOME)
                    : view('auth.verify-email');
    }
}
