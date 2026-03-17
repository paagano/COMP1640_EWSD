<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the 'faculties' table, which stores information about faculties, including their name, guest email, and guest password. The name and guest email fields are unique to ensure that no two faculties can have the same name or guest email. 
// The table also includes timestamps for tracking when records are created and updated.
return new class extends Migration
{
    // Run the migrations.
    public function up(): void
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('guest_email')->unique();
            $table->string('guest_password'); // hashed
            $table->timestamps();
        });
    }
    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('faculties');
    }
};
