<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the 'comments' table, which stores comments made by coordinators on contributions. 
// Each comment is associated with a specific contribution and a coordinator (user). The table includes fields for the comment text, the timestamp of when the comment was made, and timestamps for when records are created and updated.
return new class extends Migration
{
    
    // Run the migrations.
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contribution_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('coordinator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('comment_text');
            $table->timestamp('commented_at')->useCurrent();

            $table->timestamps();
        });
    }

    // Reverse the migrations.
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
