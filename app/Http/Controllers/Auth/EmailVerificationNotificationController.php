<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

// The EmailVerificationNotificationController class is responsible for handling email verification notifications.
// It provides a method to send a new email verification notification to the user.
// The store method checks if the user's email is already verified. If it is, the user is redirected to the home page. If not, a new email verification notification is sent to the user, and the user is redirected back with a status message indicating that the verification link has been sent.
class EmailVerificationNotificationController extends Controller
{
     // Send a new email verification notification.
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
