<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration adds a new nullable timestamp column 'published_at' to the 'contributions' table. 
// The 'published_at' column is used to track when a contribution has been published in the magazine. This allows the application to easily identify and display the publication date of each contribution. 
// The migration also includes a method to reverse these changes by dropping the 'published_at' column from the 'contributions' table if it exists, ensuring that the database schema can be rolled back if necessary
return new class extends Migration
{

    // Run the migrations.
    public function up(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable()->after('selected_at');
        });
    }

    // Reverse the migrations.
    public function down(): void
    {        Schema::table('contributions', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }
};  

