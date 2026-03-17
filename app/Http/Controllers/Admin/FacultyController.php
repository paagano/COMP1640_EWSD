<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacultyGuestCredentials;

class FacultyController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | INDEX – Intelligent Search + Pagination
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Faculty::withCount(['users', 'contributions']);

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%");

                if (is_numeric($search)) {
                    $q->orWhere('id', $search);
                }
            });

            if (is_numeric($search)) {
                $query->having('users_count', $search)
                      ->orHaving('contributions_count', $search);
            }
        }

        $faculties = $query->latest()->paginate(10);

        return view('admin.faculties.index', compact('faculties'));
    }


    /*
    |--------------------------------------------------------------------------
    | STORE – Create Faculty + Guest Account + Email Credentials
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Guest Credentials
        |--------------------------------------------------------------------------
        */

        $slug = Str::slug($request->name);

        $guestEmail = $slug . '.guest@uog.ac.uk';

        $plainPassword = Str::random(10);

        /*
        |--------------------------------------------------------------------------
        | Create Faculty
        |--------------------------------------------------------------------------
        */

        $faculty = Faculty::create([
            'name' => $request->name,
            'guest_email' => $guestEmail,
            'guest_password' => Hash::make($plainPassword)
        ]);


        /*
        |--------------------------------------------------------------------------
        | Create Guest User (for login authentication)
        |--------------------------------------------------------------------------
        */

        $guestUser = User::create([
            'name' => $request->name . ' Guest',
            'email' => $guestEmail,
            'password' => Hash::make($plainPassword),
            'faculty_id' => $faculty->id
        ]);

        // Assign Guest role (Spatie)
        $guestUser->assignRole('Guest');


        /*
        |--------------------------------------------------------------------------
        | Email Credentials to Admin
        |--------------------------------------------------------------------------
        */

        Mail::to(auth()->user()->email)
            ->send(new FacultyGuestCredentials(
                $faculty,
                $guestEmail,
                $plainPassword
            ));


        /*
        |--------------------------------------------------------------------------
        | Email Credentials to Faculty Coordinator
        |--------------------------------------------------------------------------
        */

        $coordinator = User::role('Marketing Coordinator')
            ->where('faculty_id', $faculty->id)
            ->first();

        if ($coordinator) {

            Mail::to($coordinator->email)
                ->send(new FacultyGuestCredentials(
                    $faculty,
                    $guestEmail,
                    $plainPassword
                ));
        }


        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Faculty created successfully. Guest credentials emailed.');
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE – Edit Faculty
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Faculty $faculty)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name,' . $faculty->id
        ]);

        $faculty->update([
            'name' => $request->name
        ]);

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Faculty updated successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY – Delete Faculty
    |--------------------------------------------------------------------------
    */
    public function destroy(Faculty $faculty)
    {

        if ($faculty->users()->count() > 0) {
            return redirect()
                ->route('admin.faculties.index')
                ->with('error', 'Cannot delete faculty with assigned users.');
        }

        if ($faculty->contributions()->count() > 0) {
            return redirect()
                ->route('admin.faculties.index')
                ->with('error', 'Cannot delete faculty with existing contributions.');
        }

        $faculty->delete();

        return redirect()
            ->route('admin.faculties.index')
            ->with('success', 'Faculty deleted successfully.');
    }
}