<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration creates the 'images' table, which stores image paths associated with contributions.
//Each image is linked to a contribution via a foreign key, and the table includes timestamps for tracking when records are created and updated.
return new class extends Migration
{
  
    // Run the migrations. 
     
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contribution_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('image_path');
            $table->timestamps();
        });
    }

    // Reverse the migrations. 
  
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
