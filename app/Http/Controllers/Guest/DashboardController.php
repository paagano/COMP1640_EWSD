<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\Faculty;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $facultyId = Auth::user()->faculty_id;

        $faculty = Faculty::findOrFail($facultyId);

        $publishedArticles = Contribution::where('faculty_id', $facultyId)
            ->where('status', 'published')
            ->count();

        $selectedArticles = Contribution::where('faculty_id', $facultyId)
            ->where('status', 'selected')
            ->count();

        $totalSubmissions = Contribution::where('faculty_id', $facultyId)
            ->count();

        $publishedList = Contribution::where('faculty_id', $facultyId)
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Chart Data
        |--------------------------------------------------------------------------
        */

        $submitted = Contribution::where('faculty_id', $facultyId)
            ->where('status', 'submitted')
            ->count();

        $rejected = Contribution::where('faculty_id', $facultyId)
            ->where('status', 'rejected')
            ->count();

        $statusChart = [
            'published' => $publishedArticles,
            'selected' => $selectedArticles,
            'submitted' => $submitted,
            'rejected' => $rejected
        ];

        /*
        |--------------------------------------------------------------------------
        | Monthly Submission Trend
        |--------------------------------------------------------------------------
        */

        $monthlySubmissions = Contribution::selectRaw("MONTH(created_at) as month, count(*) as total")
            ->where('faculty_id', $facultyId)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $months = [];
        $totals = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->format('M');
            $totals[] = $monthlySubmissions[$i] ?? 0;
        }

        return view('guest.dashboard', compact(
            'faculty',
            'publishedArticles',
            'selectedArticles',
            'totalSubmissions',
            'publishedList',
            'statusChart',
            'months',
            'totals'
        ));
    }

        /// Download latest magazine PDF
        public function downloadMagazine()
        {
            $path = storage_path('app/public/magazine/latest-magazine.pdf');

            if (!file_exists($path)) {
                abort(404, 'Magazine not available yet.');
            }

            return response()->download($path);
        }

}