<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the 'export_logs' table, which stores logs of export actions performed by managers. 
// Each log entry includes the ID of the manager who performed the export, the type of export (e.g., zip, csv, excel), the number of records exported, and the timestamp of when the export occurred. 
// The table also includes timestamps for tracking when records are created and updated. The migration includes
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('export_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('users');
            $table->string('export_type'); // zip / csv / excel
            $table->integer('record_count');
            $table->timestamp('exported_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('export_logs');
    }
};