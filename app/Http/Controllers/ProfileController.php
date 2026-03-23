<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\SupabaseStorage;

// The ProfileController class is responsible for handling user profile-related actions such as displaying the user's profile information, showing the form for editing the profile, and updating the profile information. 
// It uses the Auth facade to retrieve the currently authenticated user and perform actions on their profile. 
// The show method returns a view that displays the user's profile information, while the edit method returns a view that allows the user to edit their profile information. 
// The update method validates the incoming request data, updates the user's profile information in the database, and redirects back to the profile page with a success message. This controller ensures that users can view and update their profile information securely and efficiently.
class ProfileController extends Controller
{
    // Display the user's profile information.
    public function show()
    {
        $user = Auth::user();

        // Fetch last 5 login records
        $loginHistory = DB::table('activity_logs')
            ->where('user_id', $user->id)
            ->where('page', 'login')
            ->latest()
            ->limit(5)
            ->get();

        return view('profile.show', [
            'user' => $user,
            'loginHistory' => $loginHistory
        ]);
    }

    // Show the form for editing the user's profile information.
    public function edit()
    {
        $user = Auth::user();

        // Fetch last 5 login records
        $loginHistory = DB::table('activity_logs')
            ->where('user_id', $user->id)
            ->where('page', 'login')
            ->latest()
            ->limit(5)
            ->get();

        return view('profile.edit', [
            'user' => $user,
            'loginHistory' => $loginHistory
        ]);
    }

    // Update the user's profile information.
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Handle profile image upload
        // if ($request->hasFile('profile_photo')) {

        //     // delete old image
        //     if ($user->profile_photo && \Storage::exists($user->profile_photo)) {
        //         \Storage::delete($user->profile_photo);
        //     }

        //     $path = $request->file('profile_photo')->store('profile_photos', 'public');

        //     $data['profile_photo'] = $path;
        // }

         // Handle profile image upload - Using Supabase
        if ($request->hasFile('profile_photo')) {

        // OPTIONAL: delete old image (only if you were using local storage before)
        // You can skip this since Supabase URLs are external
    
        $file = $request->file('profile_photo');
    
        $fileUrl = SupabaseStorage::upload($file);
    
        if ($fileUrl) {
            $data['profile_photo'] = $fileUrl;
        } else {
            return back()->with('error', 'Image upload failed.');
        }
    }

        $user->update($data);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    // --------------------------------------------------------------------------
    // Update Password
    // --------------------------------------------------------------------------
    // Handles password change functionality.
    // Validates current password and updates to new password securely.
   
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate the request data for current password and new password with confirmation.
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.'
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully.');
    }


    // --------------------------------------------------------------------------
    // Deactivate Account
    // --------------------------------------------------------------------------
    // Disables the user account and logs them out.
    public function deactivate(Request $request)
    {
        $user = Auth::user();

        // Prevent Admin from deactivating themselves
        if ($user->hasRole('Admin')) {
            return redirect()->back()
                ->with('error', 'Admin accounts cannot be deactivated.');
        }

        // Soft deactivate
        $user->update([
            'is_active' => 0
        ]);

        // Logout user
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Your account has been deactivated. Contact Admin to reactivate.');
    }
}
