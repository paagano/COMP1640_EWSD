<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the 'users' table, which stores information about users of the system, including their name, email, password, and timestamps for when records are created and updated.
// The email field is unique to ensure that no two users can have the same email address.
return new class extends Migration
{
    
    // Run the migrations.
    
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    // Reverse the migrations.
     
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
