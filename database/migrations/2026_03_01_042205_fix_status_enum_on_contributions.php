<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration updates the 'status' enum in the 'contributions' table to include a new value 'published'. 
// The 'published' status indicates that a contribution has been selected and is now published in the magazine. 
// This change allows the application to better track the lifecycle of contributions, from submission to publication.
// The migration also includes a method to reverse these changes by removing the 'published' status from the enum, ensuring that the database schema can be rolled back if necessary. This is done by modifying the 'status' column to only include the original enum values without 'published'.
return new class extends Migration
{

    // Run the migrations.
    public function up(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->enum('status', [
                'submitted',
                'commented',
                'selected',
                'rejected',
                'published'
            ])->default('submitted')->change();
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->enum('status', [
                'submitted',
                'commented',
                'selected',
                'rejected'
            ])->default('submitted')->change();
        });
    }
};
