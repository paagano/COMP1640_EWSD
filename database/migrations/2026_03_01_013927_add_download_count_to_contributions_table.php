<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration adds a new column 'download_count' to the 'contributions' table to track the number of times each contribution has been downloaded. 
// The 'download_count' column is an unsigned integer that defaults to 0, ensuring that all contributions start with a download count of zero. 
// This allows the application to easily track and display the popularity of each contribution based on the number of downloads. 
// The migration also includes a method to reverse these changes by dropping the 'download_count' column from the 'contributions' table if it exists.
return new class extends Migration
{

    // Run the migrations.
    public function up(): void
    {
        Schema::table('contributions', function (Blueprint $table) {

            if (!Schema::hasColumn('contributions', 'download_count')) {
                $table->unsignedInteger('download_count')
                      ->default(0)
                      ->after('status');
            }

        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {

            if (Schema::hasColumn('contributions', 'download_count')) {
                $table->dropColumn('download_count');
            }

        });
    }
};