<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration adds a 'faculty_id' foreign key column to the 'users' table, which allows each user to be associated with a faculty. 
// The 'faculty_id' column is nullable, meaning that a user can exist without being associated with a faculty. 
// If a faculty is deleted, the 'faculty_id' for associated users will be set to null. The migration also includes a method to reverse these changes by dropping the foreign key and the column.
return new class extends Migration
{
    // Run the migrations.
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('faculty_id')
                  ->nullable()
                  ->after('password')
                  ->constrained()
                  ->nullOnDelete();
        });
    }

    // Reverse the migrations. 
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropColumn('faculty_id');
        });
    }
};