<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Faculty;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

// The RegisteredUserController class is responsible for handling user registration. 
// It provides methods to display the registration form and to handle the registration process. 
// The create method returns the registration view, while the store method validates the incoming registration data, creates a new user, assigns them a default role, triggers the Registered event, logs the user in, and redirects them to the home page. 
// This controller ensures that new users can register securely and that they are assigned the appropriate role upon registration, enhancing the user experience and maintaining proper access control
class RegisteredUserController extends Controller
{
     // Display the registration view.
    public function create(): View
    {
        return view('auth.register', [
            'faculties' => Faculty::orderBy('name')->get()
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class
            ],
            'faculty_id' => [
                'required',
                'exists:faculties,id'
            ],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults()
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'faculty_id' => $request->faculty_id,
            'password' => Hash::make($request->password),
        ]);

        // Automatically assign Student role. 
        // This ensures that all newly registered users have the appropriate permissions and access levels associated with the Student role, streamlining the user experience and maintaining consistent role management across the application.
        $user->assignRole('Student');

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}