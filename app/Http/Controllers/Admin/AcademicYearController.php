<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicYear::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('year_name', 'like', "%{$search}%")
                  ->orWhere('id', $search)
                  ->orWhere('is_active', $search === 'active' ? 1 : 0);
            });
        }

        $academicYears = $query
            ->orderByDesc('is_active')   // Active year appears first
            ->orderByDesc('created_at')  // then newest inactive
            ->paginate(10);

        return view('admin.academic-years.index', compact('academicYears'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'year_name' => 'required|unique:academic_years',
            'submission_closure_date' => 'required|date',
            'final_closure_date' => 'required|date|after_or_equal:submission_closure_date'
        ]);

        AcademicYear::create([
            'year_name' => $request->year_name,
            'submission_closure_date' => $request->submission_closure_date,
            'final_closure_date' => $request->final_closure_date,

            // ALWAYS create as inactive
            'is_active' => false
        ]);

        return redirect()->back()->with('success', 'Academic Year created.');
    }


    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'year_name' => 'required|unique:academic_years,year_name,' . $academicYear->id,
            'submission_closure_date' => 'required|date',
            'final_closure_date' => 'required|date|after_or_equal:submission_closure_date'
        ]);

        $academicYear->update([
            'year_name' => $request->year_name,
            'submission_closure_date' => $request->submission_closure_date,
            'final_closure_date' => $request->final_closure_date,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->back()->with('success', 'Academic Year updated.');
    }


    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();

        return redirect()->back()->with('success', 'Academic Year deleted.');
    }


    public function toggleStatus(AcademicYear $academicYear)
    {
        // If activating this year
        if (!$academicYear->is_active) {

            // Deactivate any currently active academic year
            AcademicYear::where('is_active', true)->update([
                'is_active' => false
            ]);

            // Activate selected academic year
            $academicYear->update([
                'is_active' => true
            ]);

        } else {

            // If already active, allow it to be deactivated
            $academicYear->update([
                'is_active' => false
            ]);
        }

        return redirect()->back();
    }
}