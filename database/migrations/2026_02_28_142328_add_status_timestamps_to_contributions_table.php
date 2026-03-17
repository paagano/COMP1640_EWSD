<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration adds three new timestamp columns to the 'contributions' table: 'reviewed_at', 'selected_at', and 'rejected_at'. 
// These columns are used to track the status of contributions as they go through the review process. 
// The 'reviewed_at' column indicates when a contribution was reviewed, the 'selected_at' column indicates when a contribution was selected for publication, and the 'rejected_at' column indicates when a contribution was rejected. All three columns are nullable, allowing for contributions that have not yet been reviewed, selected, or rejected. The migration also includes a method to reverse these changes by dropping the
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contributions', function (Blueprint $table) {

            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('selected_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {

            $table->dropColumn([
                'reviewed_at',
                'selected_at',
                'rejected_at'
            ]);

        });
    }
};