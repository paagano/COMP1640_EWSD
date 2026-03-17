<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Contribution;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Get Faculty Contributions
        |--------------------------------------------------------------------------
        */

        $contributions = Contribution::where('faculty_id', $user->faculty_id)
            ->latest()
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard KPIs
        |--------------------------------------------------------------------------
        */

        $total = $contributions->count();

        $submitted = $contributions->where('status', 'submitted')->count();

        $commented = $contributions->where('status', 'commented')->count();

        $selected  = $contributions->where('status', 'selected')->count();

        $rejected  = $contributions->where('status', 'rejected')->count();


        /*
        |--------------------------------------------------------------------------
        | Overdue Reviews (14 days)
        |--------------------------------------------------------------------------
        */

        $overdue = $contributions
            ->where('status', 'submitted')
            ->filter(function ($contribution) {

                return $contribution->created_at
                    ->lt(Carbon::now()->subDays(14));

            })
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Monthly Submission Trend (Chart)
        |--------------------------------------------------------------------------
        */

        $monthlySubmissions = Contribution::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('faculty_id', $user->faculty_id)
            ->groupBy('month')
            ->orderBy('month')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Prepare Chart Data
        |--------------------------------------------------------------------------
        */

        $months = [];

        $totals = [];

        foreach ($monthlySubmissions as $row) {

            $months[] = Carbon::create()
                ->month($row->month)
                ->format('M');

            $totals[] = $row->total;

        }


        /*
        |--------------------------------------------------------------------------
        | Return Dashboard View
        |--------------------------------------------------------------------------
        */

        return view('coordinator.dashboard', compact(
            'total',
            'submitted',
            'commented',
            'selected',
            'rejected',
            'overdue',
            'months',
            'totals'
        ));
    }
}