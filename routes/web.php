<?php

use Illuminate\Support\Facades\Route;
use App\Models\AcademicYear;
use Carbon\Carbon;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\ContributionController as AdminContributionController;

use App\Http\Controllers\Manager\DashboardController as ManagerDashboard;
use App\Http\Controllers\Manager\ContributionController as ManagerContributionController;
use App\Http\Controllers\Manager\ExportController;

use App\Http\Controllers\Coordinator\DashboardController as CoordinatorDashboard;
use App\Http\Controllers\Coordinator\ContributionController as CoordinatorContributionController;

use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\ContributionController as StudentContributionController;

use App\Http\Controllers\Guest\DashboardController as GuestDashboard;


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTE
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    $academicYear = AcademicYear::latest()->first();

    $daysRemaining = null;
    $submissionClosed = false;

    if ($academicYear) {

        $today = Carbon::today();
        $closureDate = Carbon::parse($academicYear->submission_closure_date);

        if ($today->gt($closureDate)) {
            $submissionClosed = true;
        } else {
            $daysRemaining = $today->diffInDays($closureDate);
        }
    }

    return view('welcome', compact(
        'academicYear',
        'daysRemaining',
        'submissionClosed'
    ));

})->name('home');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::prefix('profile')->name('profile.')->group(function () {

        Route::get('/', [ProfileController::class, 'show'])->name('show');

        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');

        Route::put('/update', [ProfileController::class, 'update'])->name('update');

        Route::put('/password', [ProfileController::class, 'updatePassword'])
            ->name('password.update');

        // Deactivate (soft delete)
        Route::delete('/deactivate', [ProfileController::class, 'deactivate'])
            ->name('deactivate');

    });


    /*
    |--------------------------------------------------------------------------
    | GENERIC DASHBOARD REDIRECT
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {

        $user = auth()->user();

        if ($user->hasRole('Admin')) return redirect()->route('admin.dashboard');
        if ($user->hasRole('Marketing Manager')) return redirect()->route('manager.dashboard');
        if ($user->hasRole('Marketing Coordinator')) return redirect()->route('coordinator.dashboard');
        if ($user->hasRole('Student')) return redirect()->route('student.dashboard');
        if ($user->hasRole('Guest')) return redirect()->route('guest.dashboard');

        return redirect()->route('home');

    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:Admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | USERS (FULL LIFECYCLE)
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        Route::post('/users', [UserController::class, 'store'])->name('users.store');

        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::post('/users/{user}/reset-password',
            [UserController::class, 'resetPassword'])->name('users.reset-password');

        // Activate / Deactivate
        Route::put('/users/{user}/deactivate', [UserController::class, 'deactivate'])
            ->name('users.deactivate');

        Route::put('/users/{user}/activate', [UserController::class, 'activate'])
            ->name('users.activate');

        // BULK ACTIONS
        Route::post('/users/bulk-activate', [UserController::class, 'bulkActivate'])
            ->name('users.bulk.activate');

        Route::post('/users/bulk-deactivate', [UserController::class, 'bulkDeactivate'])
            ->name('users.bulk.deactivate');

        Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])
            ->name('users.bulk.delete');


        /*
        |--------------------------------------------------------------------------
        | ACADEMIC YEARS
        |--------------------------------------------------------------------------
        */

        Route::get('/academic-years', [AcademicYearController::class, 'index'])
            ->name('academic-years.index');

        Route::post('/academic-years', [AcademicYearController::class, 'store'])
            ->name('academic-years.store');

        Route::put('/academic-years/{academicYear}', [AcademicYearController::class, 'update'])
            ->name('academic-years.update');

        Route::delete('/academic-years/{academicYear}', [AcademicYearController::class, 'destroy'])
            ->name('academic-years.destroy');

        Route::put('/academic-years/{academicYear}/toggle-status',
            [AcademicYearController::class, 'toggleStatus'])
            ->name('academic-years.toggle-status');


        /*
        |--------------------------------------------------------------------------
        | FACULTIES
        |--------------------------------------------------------------------------
        */

        Route::get('/faculties', [FacultyController::class, 'index'])
            ->name('faculties.index');

        Route::post('/faculties', [FacultyController::class, 'store'])
            ->name('faculties.store');

        Route::put('/faculties/{faculty}', [FacultyController::class, 'update'])
            ->name('faculties.update');

        Route::delete('/faculties/{faculty}', [FacultyController::class, 'destroy'])
            ->name('faculties.destroy');


        /*
        |--------------------------------------------------------------------------
        | CONTRIBUTIONS
        |--------------------------------------------------------------------------
        */

        Route::get('/contributions', [AdminContributionController::class, 'index'])
            ->name('contributions.index');

        Route::get('/contributions/{contribution}', [AdminContributionController::class, 'show'])
            ->name('contributions.show');

        Route::delete('/contributions/{contribution}', [AdminContributionController::class, 'destroy'])
            ->name('contributions.destroy');


        /*
        |--------------------------------------------------------------------------
        | REPORTS
        |--------------------------------------------------------------------------
        */

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports');

        Route::get('/reports/export', [ReportController::class, 'export'])
            ->name('reports.export');


        /*
        |--------------------------------------------------------------------------
        | SETTINGS
        |--------------------------------------------------------------------------
        */

        Route::get('/settings', function () {
            return view('admin.settings');
        })->name('settings');

    });


    /*
    |--------------------------------------------------------------------------
    | MARKETING MANAGER ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:Marketing Manager'])
        ->prefix('manager')
        ->name('manager.')
        ->group(function () {

        Route::get('/dashboard', [ManagerDashboard::class, 'index'])->name('dashboard');

        Route::get('/download-zip', [ExportController::class, 'downloadZip'])->name('download.zip');

        Route::get('/export-csv', [ExportController::class, 'exportCsv'])->name('export.csv');

        Route::get('/export-pdf', [ExportController::class, 'exportPdf'])->name('export.pdf');

        Route::get('/contributions/{contribution}',
            [ManagerContributionController::class, 'show'])->name('contributions.show');

        Route::post('/contributions/{contribution}/publish',
            [ManagerContributionController::class, 'publish'])->name('contributions.publish');

    });


    /*
    |--------------------------------------------------------------------------
    | MARKETING COORDINATOR ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:Marketing Coordinator'])
        ->prefix('coordinator')
        ->name('coordinator.')
        ->group(function () {

        Route::get('/dashboard', [CoordinatorDashboard::class, 'index'])->name('dashboard');

        Route::get('/contributions',
            [CoordinatorContributionController::class, 'index'])->name('contributions.index');

        Route::get('/contributions/{contribution}',
            [CoordinatorContributionController::class, 'show'])->name('contributions.show');

        Route::post('/contributions/{contribution}/update-status',
            [CoordinatorContributionController::class, 'updateStatus'])->name('contributions.updateStatus');

    });


    /*
    |--------------------------------------------------------------------------
    | STUDENT ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:Student'])
        ->prefix('student')
        ->name('student.')
        ->group(function () {

        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

        Route::get('/contributions', [StudentContributionController::class, 'index'])->name('contributions.index');

        Route::get('/contributions/create', [StudentContributionController::class, 'create'])->name('contributions.create');

        Route::post('/contributions', [StudentContributionController::class, 'store'])->name('contributions.store');

        Route::get('/contributions/{contribution}', [StudentContributionController::class, 'show'])->name('contributions.show');

        Route::get('/contributions/{contribution}/edit', [StudentContributionController::class, 'edit'])->name('contributions.edit');

        Route::put('/contributions/{contribution}', [StudentContributionController::class, 'update'])->name('contributions.update');

        Route::delete('/contributions/{contribution}', [StudentContributionController::class, 'destroy'])->name('contributions.destroy');

        Route::get('/contributions/{contribution}/download',
            [StudentContributionController::class, 'download'])->name('contributions.download');

    });


    /*
    |--------------------------------------------------------------------------
    | FACULTY GUEST PORTAL
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:Guest'])
        ->prefix('guest')
        ->name('guest.')
        ->group(function () {

        Route::get('/dashboard', [GuestDashboard::class, 'index'])->name('dashboard');

        Route::get('/download-magazine', [GuestDashboard::class, 'downloadMagazine'])
            ->name('download.magazine');

    });

    // This route allows guests to download the magazine without needing to access the dashboard first.
    Route::get('/guest/download-magazine', [App\Http\Controllers\Guest\DashboardController::class, 'downloadMagazine'])
        ->name('guest.download.magazine');

    // Password Reset Routes (accessible to guests)
    Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');
        
    Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'create'])
        ->name('password.reset');

});


require __DIR__.'/auth.php';