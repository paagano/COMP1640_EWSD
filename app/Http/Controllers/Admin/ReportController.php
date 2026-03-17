<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contribution;
use App\Models\Faculty;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        $selectedYearId = $request->academic_year;

        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        if (!$selectedYearId) {
            $activeYear = AcademicYear::where('is_active', true)->first();
            $selectedYearId = $activeYear?->id ?? $academicYears->first()?->id;
        }


        // --------------------------------------------------------------------------------------------------------------------------------------------------
        // QUICK REPORTS SECTION: Provides key metrics and insights about contributions, faculty performance, and SLA breaches for the selected academic year.
        // --------------------------------------------------------------------------------------------------------------------------------------------------

        $contributionQuery = Contribution::query();

        if ($selectedYearId) {
            $contributionQuery->where('academic_year_id', $selectedYearId);
        }

        $totalContributions = $contributionQuery->count();


        $facultyStats = Faculty::leftJoin('contributions', function ($join) use ($selectedYearId) {

                $join->on('contributions.faculty_id', '=', 'faculties.id');

                if ($selectedYearId) {
                    $join->where('contributions.academic_year_id', '=', $selectedYearId);
                }

            })
            ->select(
                'faculties.id',
                'faculties.name',
                DB::raw('COUNT(contributions.id) as total_contributions')
            )
            ->groupBy('faculties.id', 'faculties.name')
            ->get()
            ->map(function ($faculty) use ($totalContributions) {

                $faculty->percentage = $totalContributions > 0
                    ? round(($faculty->total_contributions / $totalContributions) * 100, 2)
                    : 0;

                return $faculty;
            });


        $contributorsPerFaculty = Faculty::leftJoin('contributions', function ($join) use ($selectedYearId) {

                $join->on('contributions.faculty_id', '=', 'faculties.id');

                if ($selectedYearId) {
                    $join->where('contributions.academic_year_id', '=', $selectedYearId);
                }

            })
            ->select(
                'faculties.id',
                'faculties.name',
                DB::raw('COUNT(DISTINCT contributions.student_id) as unique_contributors')
            )
            ->groupBy('faculties.id', 'faculties.name')
            ->get();


        $noCommentContributions = Contribution::whereDoesntHave('comments')
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->with(['student', 'faculty', 'faculty.coordinator'])
            ->get();


        $slaBreaches = Contribution::whereDoesntHave('comments')
            ->where('created_at', '<', Carbon::now()->subDays(14))
            ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
            ->with(['student', 'faculty', 'faculty.coordinator'])
            ->get();


   
        // -------------------------------------------------------------------------------------------------------------------------
        // ANALYTICS SECTION: Provides insights into user behavior, popular pages, and browser usage based on the activity_logs data.
        // -------------------------------------------------------------------------------------------------------------------------
   
        // -------------------------------------------------------------------------------------------------
        //  Most Viewed Pages: Ranks pages based on the number of visits recorded in the activity_logs table.
        // -------------------------------------------------------------------------------------------------

        $pageViews = DB::table('activity_logs')
            ->select('page', DB::raw('COUNT(*) as total'))
            ->groupBy('page')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $pageViewLabels = $pageViews->pluck('page');
        $pageViewData = $pageViews->pluck('total');

        $topPages = $pageViews;

        // -------------------------------------------------------------------------------------------------------
        //  Most Active Users: Ranks users based on the number of page visits recorded in the activity_logs table.
        // -------------------------------------------------------------------------------------------------------
    
        $activeUsers = DB::table('activity_logs')
            ->join('users', 'users.id', '=', 'activity_logs.user_id')
            ->select('users.name', DB::raw('COUNT(activity_logs.id) as total'))
            ->groupBy('users.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $activeUserLabels = $activeUsers->pluck('name');
        $activeUserData = $activeUsers->pluck('total');
       
        // ------------------------------------------------------------------------------------------------------------------------------------
        //  Browser Usage: Analyzes the 'browser' column in the activity_logs table to determine the distribution of browsers used by visitors.
        // ------------------------------------------------------------------------------------------------------------------------------------

        // 'browser' is a column in the 'activity_logs' table that stores the browser name.
        $browsers = DB::table('activity_logs')
            ->select('browser', DB::raw('COUNT(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->get();

        $browserLabels = $browsers->pluck('browser');
        $browserData = $browsers->pluck('total');

       
        // ---------------------------------------------------------------------------------------------------------------------------------
        //  New vs Returning Users: Compares the number of new users (based on registration date) to returning users, using the users table.
        // ---------------------------------------------------------------------------------------------------------------------------------
        
        // For simplicity, "new" users are defined as those who registered in the last month, and "returning" as everyone else.
        $newUsers = User::whereDate('created_at', '>=', now()->subMonth())->count();
        $returningUsers = User::count() - $newUsers;

        $userTypeData = [$newUsers, $returningUsers];


       
        // -------------------------------------------------------------------------------------------------------------------------------------------------
        //  Active Users Last 5 Minutes: Counts the number of unique users who have visited any page in the last 5 minutes, based on the activity_logs data.
        // -------------------------------------------------------------------------------------------------------------------------------------------------

        // Count distinct user_ids from activity_logs in the last 5 minutes
        $activeUsersNow = DB::table('activity_logs')
            ->where('created_at', '>=', now()->subMinutes(5)) 
            ->distinct('user_id')
            ->count('user_id');


        // ----------------------------------------------------------------------------------------------------------------------------
        // MONTHLY ACTIVITY: Shows the total number of page visits for each month of the current year, based on the activity_logs data.
        // ----------------------------------------------------------------------------------------------------------------------------

        // Always returns 12 months so the chart never appears empty, even if there are no visits in some months.
        $monthlyRaw = DB::table('activity_logs')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        $monthlyLabels = [];
        $monthlyData = [];

        for ($m = 1; $m <= 12; $m++) {

            $monthlyLabels[] = Carbon::create()->month($m)->format('M');

            $monthlyData[] = $monthlyRaw[$m] ?? 0;
        }

        // -----------------------------------------------------------------------------------------
        // RECENT ACTIVITY FEED: Shows the 10 most recent page visits with user names and timestamps.
        // -----------------------------------------------------------------------------------------

        $recentActivity = DB::table('activity_logs')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->select(
                'users.name',
                'activity_logs.page',
                'activity_logs.created_at'
            )
            ->latest('activity_logs.created_at')
            ->limit(10) // Get the 10 most recent activity log entries
            ->get();

        // Pass all the data to the view
        return view('admin.reports.index', compact(
            'academicYears',
            'selectedYearId',
            'facultyStats',
            'contributorsPerFaculty',
            'noCommentContributions',
            'slaBreaches',
            'totalContributions',

            'pageViewLabels',
            'pageViewData',
            'activeUserLabels',
            'activeUserData',
            'browserLabels',
            'browserData',
            'userTypeData',
            'topPages',
            'activeUsersNow',
            'monthlyLabels',
            'monthlyData',
            'recentActivity'
        ));
    }

    // ----------------------------------------------------------------------------------------------------------------------
    //  EXPORT METHOD: Handles exporting reports in CSV, Excel, and PDF formats based on the selected type and academic year.
    // ----------------------------------------------------------------------------------------------------------------------

    // Note: For simplicity, Excel export is treated the same as CSV since Excel can open CSV files. In a real application, I'd use a library like PhpSpreadsheet for true Excel exports.
        public function export(Request $request)
        {
            $type = $request->type;
            $format = $request->format;
            $selectedYearId = $request->academic_year;

            switch ($type) {

                case 'faculty':

                    $data = Faculty::leftJoin('contributions', function ($join) use ($selectedYearId) {

                            $join->on('contributions.faculty_id', '=', 'faculties.id');

                            if ($selectedYearId) {
                                $join->where('contributions.academic_year_id', '=', $selectedYearId);
                            }

                        })
                        ->select(
                            'faculties.name',
                            DB::raw('COUNT(contributions.id) as total_contributions')
                        )
                        ->groupBy('faculties.name')
                        ->get()
                        ->toArray();

                    $headers = ['Faculty', 'Total Contributions'];

                break;

                case 'contributors':

                    $data = Faculty::leftJoin('contributions', function ($join) use ($selectedYearId) {

                            $join->on('contributions.faculty_id', '=', 'faculties.id');

                            if ($selectedYearId) {
                                $join->where('contributions.academic_year_id', '=', $selectedYearId);
                            }

                        })
                        ->select(
                            'faculties.name',
                            DB::raw('COUNT(DISTINCT contributions.student_id) as unique_contributors')
                        )
                        ->groupBy('faculties.name')
                        ->get()
                        ->toArray();

                    $headers = ['Faculty', 'Unique Contributors'];

                break;

                case 'no_comment':

                    $data = Contribution::whereDoesntHave('comments')
                        ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
                        ->with(['student', 'faculty', 'faculty.coordinator'])
                        ->get()
                        ->map(fn($c) => [

                            'title' => $c->title,
                            'student' => $c->student->name ?? 'N/A',
                            'faculty' => $c->faculty->name ?? 'N/A',
                            'faculty_coordinator' => $c->faculty->coordinator->name ?? 'N/A',
                            'date_submitted' => $c->created_at->format('Y-m-d')

                        ])
                        ->toArray();

                    $headers = ['Title', 'Student', 'Faculty', 'Faculty Coordinator', 'Date Submitted'];

                break;

                case 'sla':

                    $data = Contribution::whereDoesntHave('comments')
                        ->where('created_at', '<', Carbon::now()->subDays(14))
                        ->when($selectedYearId, fn($q) => $q->where('academic_year_id', $selectedYearId))
                        ->with(['student', 'faculty', 'faculty.coordinator'])
                        ->get()
                        ->map(fn($c) => [

                            'title' => $c->title,
                            'student' => $c->student->name ?? 'N/A',
                            'faculty' => $c->faculty->name ?? 'N/A',
                            'faculty_coordinator' => $c->faculty->coordinator->name ?? 'N/A',
                            'date_submitted' => $c->created_at->format('Y-m-d'),
                            'days_pending' => $c->created_at->diffInDays(now())

                        ])
                        ->toArray();

                    $headers = ['Title', 'Student', 'Faculty', 'Faculty Coordinator', 'Date Submitted', 'Days Pending'];

                break;

                // -----------------------------------------------------------------------------------------------------------------------------------------
                // TOP PAGES REPORT: Ranks the most visited pages based on the activity_logs data, showing visit counts and percentage of total visits.
                // -----------------------------------------------------------------------------------------------------------------------------------------

                case 'top_pages':

                    $pages = DB::table('activity_logs')
                        ->select('page', DB::raw('COUNT(*) as total'))
                        ->groupBy('page')
                        ->orderByDesc('total')
                        ->limit(10)
                        ->get();

                    $totalVisits = $pages->sum('total');

                    $data = $pages->map(function ($page, $index) use ($totalVisits) {

                        $percentage = $totalVisits > 0
                            ? round(($page->total / $totalVisits) * 100, 2)
                            : 0;

                        return [
                            'rank' => $index + 1,
                            'page' => $page->page,
                            'visits' => $page->total,
                            'percentage' => $percentage . '%'
                        ];

                    })->toArray();

                    $headers = ['Rank', 'Page', 'Visits', '% of Total'];

                break;

                // -----------------------------------------------------------------------------------------------------------------------------------------
                // ACTIVITY FEED EXPORT: Exports the recent activity feed as a simple text file, with one entry per line.
                // ------------------------------------------------------------------------------------------------------------
                
                case 'activity_feed':

                    $data = DB::table('activity_logs')
                        ->join('users', 'activity_logs.user_id', '=', 'users.id')
                        ->select(
                            'users.name',
                            'activity_logs.page',
                            'activity_logs.created_at'
                        )
                        ->latest('activity_logs.created_at')
                        ->limit(100)
                        ->get()
                        ->map(function ($log) {

                            return [
                                'entry' => $log->created_at . ' - ' .
                                        $log->name . ' viewed ' .
                                        $log->page
                            ];

                        })
                        ->toArray();

                    $headers = ['Activity Log'];

                break;

                default:
                    abort(404);

            }

            // -----------------------------------------------------------------------------------------
            // TXT EXPORT (For Activity Feed): Simply outputs a plain text file with one entry per line.
            // -----------------------------------------------------------------------------------------

            if ($format === 'txt') {

                $filename = $type . '_report.txt';

                return response()->streamDownload(function () use ($data) {

                    $handle = fopen('php://output', 'w');

                    foreach ($data as $row) {
                        fwrite($handle, implode(' ', $row) . PHP_EOL);
                    }

                    fclose($handle);

                }, $filename);
            }

            // ------------------------------------------------------------------------------------------------------------
            // CSV / EXCEL EXPORT: Outputs a CSV file that can be opened in Excel, with proper headers and data formatting.
            // ------------------------------------------------------------------------------------------------------------

            if ($format === 'csv' || $format === 'excel') {

                $filename = $type . '_report.csv';

                return response()->streamDownload(function () use ($data, $headers) {

                    $handle = fopen('php://output', 'w');

                    fputcsv($handle, $headers);

                    foreach ($data as $row) {
                        fputcsv($handle, array_values($row));
                    }

                    fclose($handle);

                }, $filename);

            }

            // ---------------------------------------------------------------------------------------
            // PDF EXPORT: Uses a Blade view to format the data and generates a PDF file for download.
            // ---------------------------------------------------------------------------------------

            elseif ($format === 'pdf') {

                $pdf = Pdf::loadView('admin.reports.export_pdf', [
                    'data' => $data,
                    'headers' => $headers,
                    'title' => ucfirst($type) . ' Report'
                ]);

                return $pdf->download($type . '_report.pdf');
            }

            abort(404);
        }
}