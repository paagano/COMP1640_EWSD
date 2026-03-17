<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the 'academic_years' table, which stores information about academic years, including the year name, submission closure date, and final closure date. The table also includes timestamps for tracking when records are created and updated.
return new class extends Migration
{
    
    // Run the migrations.
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('year_name', 20);
            $table->date('submission_closure_date');
            $table->date('final_closure_date');
            $table->timestamps();
        });
    }


    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
