<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST CONTRIBUTIONS
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Contribution::with(['student', 'faculty', 'academicYear']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->faculty) {
            $query->where('faculty_id', $request->faculty);
        }

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        $contributions = $query->latest()->paginate(9);

        return view('admin.contributions.index', compact('contributions'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW CONTRIBUTION
    |--------------------------------------------------------------------------
    */
    public function show(Contribution $contribution)
    {
        $contribution->load(['student', 'faculty', 'academicYear', 'images']);

        return view('admin.contributions.show', compact('contribution'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT CONTRIBUTION
    |--------------------------------------------------------------------------
    */
    public function edit(Contribution $contribution)
    {
        return view('admin.contributions.edit', compact('contribution'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE CONTRIBUTION
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Contribution $contribution)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $contribution->update([
            'status' => $request->status
        ]);

        return redirect()
            ->route('admin.contributions.show', $contribution)
            ->with('success', 'Contribution updated successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE CONTRIBUTION
    |--------------------------------------------------------------------------
    */
    public function destroy(Contribution $contribution)
    {

        /*
        |--------------------------------------------------------------------------
        | Back-End Validation:Prevent deleting selected or published contributions
        |--------------------------------------------------------------------------
        */

        if (in_array($contribution->status, ['selected', 'published'])) {

            return redirect()
                ->route('admin.contributions.index')
                ->with('error', 'Selected or Published contributions cannot be deleted.');
        }

        $contribution->delete();

        return back()->with('success', 'Contribution deleted successfully.');
    }
}