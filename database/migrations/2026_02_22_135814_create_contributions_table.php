<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// This migration creates the 'contributions' table, which stores information about contributions made by students. 
// Each contribution includes details such as title, content summary, word document path, status, and timestamps.
// The table also establishes foreign key relationships with the 'users', 'faculties', and 'academic_years' tables to link contributions to specific students, faculties, and academic years.

return new class extends Migration
{
     // Run the migrations.
     
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();

            $table->string('title',255);
            $table->text('content_summary')->nullable();
            $table->string('word_document_path');

            $table->enum('status', [
                'submitted',
                'commented',
                'selected',
                'rejected'
            ])->default('submitted');

            $table->index('status');

            $table->boolean('agreed_terms')->default(false);

            $table->foreignId('student_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('faculty_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('academic_year_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    // Reverse the migrations.

    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
