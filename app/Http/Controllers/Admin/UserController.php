<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Faculty;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['roles', 'faculty']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        $users = $query->latest()->paginate(10);

        $roles = Role::all();
        $faculties = Faculty::orderBy('name')->get();

        return view('admin.users.index', compact(
            'users',
            'roles',
            'faculties'
        ));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'faculty_id' => 'required|exists:faculties,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'faculty_id' => $request->faculty_id,
            'is_active' => 1 // Ensure new users are active by default
        ]);

        $user->assignRole($request->role);

        return redirect()->back()
            ->with('success', 'User created successfully.');
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',
            'faculty_id' => 'required|exists:faculties,id'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'faculty_id' => $request->faculty_id
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->back()
            ->with('success', 'User updated successfully.');
    }


    // --------------------------------------------------------------------------
    // Deactivate User (Admin)
    // --------------------------------------------------------------------------
    public function deactivate(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot deactivate your own account.');
        }

        $user->update([
            'is_active' => 0
        ]);

        return redirect()->back()
            ->with('success', 'User deactivated successfully.');
    }


    // --------------------------------------------------------------------------
    // Activate User (Admin)
    // --------------------------------------------------------------------------
    public function activate(User $user)
    {
        $user->update([
            'is_active' => 1
        ]);

        return redirect()->back()
            ->with('success', 'User activated successfully.');
    }


    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.'); // Prevent self-deletion
        }

        $user->delete();

        return redirect()->back()
            ->with('success', 'User deleted successfully.');
    }


    // --------------------------------------------------------------------------
    // BULK ACTIVATE
    // --------------------------------------------------------------------------
    public function bulkActivate(Request $request)
    {
        if (!$request->filled('user_ids')) {
            return back()->with('error', 'No users selected.');
        }

        $ids = array_filter($request->user_ids, function ($id) {
            return $id != auth()->id(); // prevent self-action
        });

        User::whereIn('id', $ids)->update(['is_active' => 1]);

        return back()->with('success', 'Selected users activated.');
    }


    // --------------------------------------------------------------------------
    // BULK DEACTIVATE
    // --------------------------------------------------------------------------
    public function bulkDeactivate(Request $request)
    {
        if (!$request->filled('user_ids')) {
            return back()->with('error', 'No users selected.');
        }

        $ids = array_filter($request->user_ids, function ($id) {
            return $id != auth()->id(); // prevent self-action
        });

        User::whereIn('id', $ids)->update(['is_active' => 0]);

        return back()->with('success', 'Selected users deactivated.');
    }

    // --------------------------------------------------------------------------
    // BULK DELETE
    // --------------------------------------------------------------------------
    public function bulkDelete(Request $request)
    {
        if (!$request->filled('user_ids')) {
            return back()->with('error', 'No users selected.');
        }

        $ids = array_filter($request->user_ids, fn($id) => $id != auth()->id());

        User::whereIn('id', $ids)->delete();

        return back()->with('success', 'Selected users deleted.');
    }


    public function resetPassword(User $user)
    {
        $newPassword = Str::random(10);

        $user->update([
            'password' => Hash::make($newPassword),
            'password_reset_expires_at' => Carbon::now()->addMinutes(5)
        ]);

        Mail::raw(
            "Your temporary password is: {$newPassword}\n\nThis password is valid for 5 minutes.",
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Temporary Password Reset');
            }
        );

        return redirect()->back()
            ->with('success', 'Temporary password sent to user email.');
    }
}