<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the 'failed_jobs' table, which is used to store information about failed jobs in the application. Each record includes a unique identifier (UUID), the connection and queue where the job was attempted, the payload of the job, the exception message, and a timestamp for when the job failed. 
// The UUID field is unique to ensure that each failed job can be uniquely identified.
return new class extends Migration
{

    // Run the migrations.
    public function up(): void
    {
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }

     // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('failed_jobs');
    }
};
