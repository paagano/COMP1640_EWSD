<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\SupabaseStorage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

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

    public function edit()
    {
        $user = Auth::user();

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

        /**
         * HANDLE PROFILE IMAGE UPLOAD (WITH CLEANUP)
         */
        if ($request->hasFile('profile_photo')) {

            $file = $request->file('profile_photo');

            // DELETE OLD FILE (SUPABASE OR LOCAL SAFE)
            if ($user->profile_photo) {

                // Try Supabase delete (safe no-op if local)
                SupabaseStorage::delete($user->profile_photo);

                // Also attempt local delete (safe fallback)
                $oldPath = str_replace(asset('storage/'), '', $user->profile_photo);

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            // Upload new file
            $fileUrl = SupabaseStorage::upload($file, 'profile_photos');

            if ($fileUrl) {
                $data['profile_photo'] = $fileUrl;
            } else {
                return back()->with('error', 'Image upload failed. Please try again.');
            }
        }

        $user->update($data);

        return redirect()->route('profile.show')
            ->with('success', 'Profile updated successfully.');
    }

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