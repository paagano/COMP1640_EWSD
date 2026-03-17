<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the 'activity_logs' table, which is designed to store detailed logs of user activities and page visits within the application. 
// The table includes references to the user (if logged in), the page visited, the HTTP method used, browser and platform information, and the IP address of the visitor. 
// This data can be used for analytics, monitoring user behavior, and improving the overall user experience. The migration also includes indexes on key columns to optimize query performance when analyzing activity logs. The 'user_id' column is nullable to accommodate visits from guests or before login, and it is set to null on delete to preserve activity logs even if a user account is removed.
return new class extends Migration
{
    
    // Run the migrations.
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {

            $table->id();

          
            // User Reference
            // Nullable because some visits may occur before login or by guests      
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            
            // Page Information
            $table->string('page');                 // e.g. admin/dashboard
            $table->string('method')->nullable();   // GET / POST
           
            // Browser / Device
            $table->string('browser')->nullable();  // Chrome, Firefox etc
            $table->string('platform')->nullable(); // Windows, Mac, Android
            
            // Network Information
            $table->string('ip_address')->nullable();
           
            // Laravel timestamps
            $table->timestamps();

            // Performance Indexes (for analytics queries)
            $table->index('page');
            $table->index('user_id');
            $table->index('browser');
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};