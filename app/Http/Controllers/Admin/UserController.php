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
        $query = User::with(['roles','faculty']);

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
            'faculty_id' => $request->faculty_id
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


    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->back()
            ->with('success', 'User deleted successfully.');
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