<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the 'password_reset_tokens' table, which stores tokens for password reset functionality. Each record includes the user's email, a unique token for resetting the password, and a timestamp for when the token was created. 
// The email field is set as the primary key to ensure that each user can only have one active password reset token at a time.
return new class extends Migration
{
  
    // Run the migrations.
   
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

   
     // Reverse the migrations.
  
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};
