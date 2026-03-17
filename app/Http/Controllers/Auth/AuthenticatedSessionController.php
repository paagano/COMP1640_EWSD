<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
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

        /*
        |--------------------------------------------------------------------------
        | Role-based Redirection
        |--------------------------------------------------------------------------
        */

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

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}