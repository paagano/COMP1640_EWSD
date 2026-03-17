<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

// The AuthenticatedSessionController class is responsible for handling user authentication sessions. 
// It provides methods for displaying the login view, processing login requests, and logging out users. 
// The create method returns the login view, allowing users to enter their credentials. 
// The store method handles the authentication process, including validating the user's credentials, regenerating the session for security, and redirecting users based on their roles. 
// The destroy method logs out the user, invalidates the session, and regenerates the CSRF token to ensure security. 
// This controller is essential for managing user access to the application and ensuring that users are directed to the appropriate dashboard based on their roles, such as Admin, Marketing Manager, Marketing Coordinator, Student, or Guest. 
// By implementing role-based redirection, the application can provide a personalized experience for each user type, ensuring that they have access to the features and information relevant to their role within the system.
class AuthenticatedSessionController extends Controller
{

    // Display the login view.
    public function create(): View
    {
        return view('auth.login');
    }

     // Handle an incoming authentication request.
    public function store(LoginRequest $request): RedirectResponse
    {
        // Authenticate user
        $request->authenticate();

        // Regenerate session (security best practice)
        $request->session()->regenerate();

        $user = $request->user();

        // Store the previous login timestamp in session
        // This allows the dashboard to display the last login
        session(['last_login_at' => $user->last_login_at]);

        // Update the user's last login timestamp to now
        $user->last_login_at = now();
        $user->save();

        // --------------------------------------------------------------------------
        // Role-based Redirection
        // --------------------------------------------------------------------------
        if ($user->hasRole('Admin')) {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->hasRole('Marketing Manager')) {
            return redirect()->intended(route('manager.dashboard'));
        }

        if ($user->hasRole('Marketing Coordinator')) {
            return redirect()->intended(route('coordinator.dashboard'));
        }

        if ($user->hasRole('Student')) {
            return redirect()->intended(route('student.dashboard'));
        }

        if ($user->hasRole('Guest')) {
            return redirect()->intended(route('guest.dashboard'));
        }

        // Fallback
        return redirect('/');
    }

    // Destroy an authenticated session.
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}