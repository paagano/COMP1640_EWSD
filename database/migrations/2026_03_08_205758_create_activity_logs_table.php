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
        Schema::create('activity_logs', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | User Reference
            |--------------------------------------------------------------------------
            | Nullable because some visits may occur before login
            */
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Page Information
            |--------------------------------------------------------------------------
            */
            $table->string('page');                 // e.g. admin/dashboard
            $table->string('method')->nullable();   // GET / POST


            /*
            |--------------------------------------------------------------------------
            | Browser / Device
            |--------------------------------------------------------------------------
            */
            $table->string('browser')->nullable();  // Chrome, Firefox etc
            $table->string('platform')->nullable(); // Windows, Mac, Android


            /*
            |--------------------------------------------------------------------------
            | Network Information
            |--------------------------------------------------------------------------
            */
            $table->string('ip_address')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Laravel timestamps
            |--------------------------------------------------------------------------
            */
            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Performance Indexes (for analytics queries)
            |--------------------------------------------------------------------------
            */
            $table->index('page');
            $table->index('user_id');
            $table->index('browser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};