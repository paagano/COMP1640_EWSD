<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;

// The AcademicYearSeeder is responsible for seeding the academic_years table with initial data. 
// It creates a new academic year with a specified year name, submission closure date, and final closure date. 
// This seeder can be used to populate the database with a default academic year when setting up the application for the first time or during testing. By running this seeder, developers can ensure that there is at least one academic year available in the system, which can be useful for testing features that depend on the existence of academic years, such as contribution submissions and active year management.
class AcademicYearSeeder extends Seeder
{
    // Run the database seeds.
    public function run(): void
    {
        AcademicYear::create([
            'year_name' => '2025/2026',
            'submission_closure_date' => now()->addMonth(),
            'final_closure_date' => now()->addMonths(2),
        ]);
    }
}