<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

// The PasswordController class is responsible for handling password updates for authenticated users. 
// It provides a method to update the user's password, which validates the current password and the new password before updating it in the database. 
// If the password update is successful, the user is redirected back with a status message indicating that the password has been updated. 
// This controller ensures that users can securely update their passwords while enforcing password strength requirements and confirming the current password for added security.
class PasswordController extends Controller
{
     // Update the user's password.
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']), // Hash the new password before saving it to the database for security.
        ]);

        return back()->with('status', 'password-updated');
    }
}
