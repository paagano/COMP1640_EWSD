<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contribution;
use App\Models\Faculty;
use App\Models\User;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ============================
        // Academic Years
        // ============================
        $academicYears = AcademicYear::orderByDesc('submission_closure_date')->get();
        $activeYear = AcademicYear::active()->first();

        // Multi-year selection support
        $selectedYearIds = $request->academic_year;

        if (!$selectedYearIds) {
            $selectedYearIds = $activeYear ? [$activeYear->id] : [];
        }

        $selectedYearIds = is_array($selectedYearIds)
            ? $selectedYearIds
            : [$selectedYearIds];

        // For deadline logic use first selected year
        $academicYear = AcademicYear::whereIn('id', $selectedYearIds)->first();

        // ============================
        // Deadline Logic
        // ============================
        $submissionClosed = false;
        $daysRemaining = null;

        if ($academicYear && $academicYear->final_closure_date) {
            $today = Carbon::today();
            $deadline = Carbon::parse($academicYear->final_closure_date);

            if ($today->greaterThan($deadline)) {
                $submissionClosed = true;
            } else {
                $daysRemaining = $today->diffInDays($deadline);
            }
        }

        // ============================
        // Base Contribution Query
        // ============================
        $contributionQuery = Contribution::query()
            ->when($selectedYearIds, function ($q) use ($selectedYearIds) {
                $q->whereIn('academic_year_id', $selectedYearIds);
            });

        $baseQuery = clone $contributionQuery;

        // ============================
        // Global Statistics
        // ============================
        $totalContributions = $baseQuery->count();
        $totalStudents = User::role('student')->count(); // Spatie
        $totalFaculties = Faculty::count();

        // ============================
        // Status Counts
        // ============================
        $statusCounts = (clone $contributionQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $statusCounts = array_merge([
            'submitted' => 0,
            'commented' => 0,
            'selected'  => 0,
            'rejected'  => 0,
        ], $statusCounts);

        // ============================
        // SLA (14+ days pending)
        // ============================
        $overdueCount = Contribution::where('status', 'submitted')
            ->where('created_at', '<', Carbon::now()->subDays(14))
            ->when($selectedYearIds, function ($q) use ($selectedYearIds) {
                $q->whereIn('academic_year_id', $selectedYearIds);
            })
            ->count();

        // ============================
        // Faculty Statistics
        // ============================
        $facultyStats = Faculty::leftJoin('contributions', function ($join) use ($selectedYearIds) {
                $join->on('contributions.faculty_id', '=', 'faculties.id');

                if (!empty($selectedYearIds)) {
                    $join->whereIn('contributions.academic_year_id', $selectedYearIds);
                }
            })
            ->select(
                'faculties.id',
                'faculties.name',
                DB::raw('COUNT(contributions.id) as total_contributions'),
                DB::raw("SUM(CASE WHEN contributions.status = 'submitted' THEN 1 ELSE 0 END) as submitted_count"),
                DB::raw("SUM(CASE WHEN contributions.status = 'commented' THEN 1 ELSE 0 END) as commented_count"),
                DB::raw("SUM(CASE WHEN contributions.status = 'selected' THEN 1 ELSE 0 END) as selected_count"),
                DB::raw("SUM(CASE WHEN contributions.status = 'rejected' THEN 1 ELSE 0 END) as rejected_count")
            )
            ->groupBy('faculties.id', 'faculties.name')
            ->get();

        // ============================
        // Faculty Ranking (Weighted KPI)
        // ============================
        $facultyRanking = $facultyStats->map(function ($faculty) {

            $faculty->performance_score =
                ($faculty->selected_count * 3) +
                ($faculty->commented_count * 1) -
                ($faculty->rejected_count * 2);

            return $faculty;

        })->sortByDesc('performance_score')->values();

        // ============================
        // Chart Data
        // ============================
        $facultyNames = $facultyStats->pluck('name');
        $facultyTotals = $facultyStats->pluck('total_contributions');

        // ============================
        // Monthly Trend
        // ============================
        $trendData = Contribution::select(
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month_label"),
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month_key"),
                DB::raw("COUNT(*) as total")
            )
            ->when($selectedYearIds, function ($q) use ($selectedYearIds) {
                $q->whereIn('academic_year_id', $selectedYearIds);
            })
            ->groupBy('month_key', 'month_label')
            ->orderBy('month_key')
            ->get();

        $trendMonths = $trendData->pluck('month_label');
        $trendCounts = $trendData->pluck('total');

        return view('admin.dashboard', compact(
            'academicYears',
            'activeYear',
            'academicYear',
            'selectedYearIds',
            'totalContributions',
            'totalStudents',
            'totalFaculties',
            'statusCounts',
            'facultyStats',
            'facultyRanking',
            'facultyNames',
            'facultyTotals',
            'trendMonths',
            'trendCounts',
            'overdueCount',
            'submissionClosed',
            'daysRemaining'
        ));
    }
}