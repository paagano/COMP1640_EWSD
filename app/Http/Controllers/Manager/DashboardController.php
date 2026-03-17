<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Contribution;
use App\Models\AcademicYear;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get latest academic year
        $academicYear = AcademicYear::latest()->first();

        // Fetch all selected and published contributions
        $selectedContributions = Contribution::whereIn('status', ['selected', 'published'])
            ->with(['student', 'faculty'])
            ->latest()
            ->paginate(5);

        $finalClosurePassed = false;

        if ($academicYear) {
            $finalClosurePassed = Carbon::today()
                ->gte(Carbon::parse($academicYear->final_closure_date));
        }

        return view('manager.dashboard', compact(
            'selectedContributions',
            'academicYear',
            'finalClosurePassed'
        ));
    }
}