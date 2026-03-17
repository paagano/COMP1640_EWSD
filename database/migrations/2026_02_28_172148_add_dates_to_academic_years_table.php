<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {

            // Add start and end dates
            $table->date('start_date')->nullable()->after('year_name');
            $table->date('end_date')->nullable()->after('start_date');

            // Optional but highly recommended
            // Allows marking one academic year as active
            $table->boolean('is_active')->default(false)->after('end_date');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('academic_years', function (Blueprint $table) {

            $table->dropColumn(['start_date', 'end_date', 'is_active']);

        });
    }
};