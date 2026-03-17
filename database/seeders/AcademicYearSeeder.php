<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        AcademicYear::create([
            'year_name' => '2025/2026',
            'submission_closure_date' => now()->addMonth(),
            'final_closure_date' => now()->addMonths(2),
        ]);
    }
}