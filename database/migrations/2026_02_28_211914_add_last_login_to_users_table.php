<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration adds a new column 'last_login_at' to the 'users' table to track the last login time of each user. 
// The 'last_login_at' column is a nullable timestamp that will store the date and time of the user's last login. This information can be useful for monitoring user activity and identifying inactive accounts.
// The migration also includes a method to reverse these changes by dropping the 'last_login_at' column from the 'users' table.
return new class extends Migration
{

    // Run the migrations.
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')
                  ->nullable()
                  ->after('remember_token');
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login_at');
        });
    }
};