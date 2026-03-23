<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Services\SupabaseStorage;

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

<<<<<<< HEAD
        /**
         * HANDLE PROFILE IMAGE UPLOAD
         */
        if ($request->hasFile('profile_photo')) {

            $file = $request->file('profile_photo');

            // Upload (auto-switches local vs Supabase)
            $fileUrl = SupabaseStorage::upload($file, 'profile_photos');

            if ($fileUrl) {

                /**
                 * OPTIONAL CLEANUP (LOCAL ONLY)
                 * Only delete old file if:
                 * - We are in LOCAL env
                 * - Old file exists and is local (not Supabase URL)
                 */
                if (app()->environment('local') && $user->profile_photo) {

                    $oldPath = str_replace(asset('storage/') , '', $user->profile_photo);

                    if (\Storage::disk('public')->exists($oldPath)) {
                        \Storage::disk('public')->delete($oldPath);
                    }
                }

                $data['profile_photo'] = $fileUrl;

            } else {
                return back()->with('error', 'Image upload failed. Please try again.');
            }
=======
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
>>>>>>> 8ee02d48b0ed6145be52f059aba7b7469bdcc4cb
        }
    }

        $user->update($data);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }

    // --------------------------------------------------------------------------
    // Update Password
    // --------------------------------------------------------------------------
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    // --------------------------------------------------------------------------
    // Deactivate Account
    // --------------------------------------------------------------------------
    public function deactivate(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('Admin')) {
            return redirect()->back()
                ->with('error', 'Admin accounts cannot be deactivated.');
        }

        $user->update([
            'is_active' => 0
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Your account has been deactivated. Contact Admin to reactivate.');
    }
}
