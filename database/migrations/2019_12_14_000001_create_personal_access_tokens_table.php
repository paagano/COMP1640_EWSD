<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the 'personal_access_tokens' table, which is used to store personal access tokens for authentication. 
// Each token is associated with a tokenable model (which can be any model that uses the HasApiTokens trait), and includes fields for the token's name, the token itself, its abilities, last used timestamp, and expiration timestamp. The table also includes timestamps for when records are created and updated.
return new class extends Migration
{
  
    // Run the migrations.
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
