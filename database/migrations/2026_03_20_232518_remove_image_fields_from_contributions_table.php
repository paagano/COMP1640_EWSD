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
        Schema::table('contributions', function (Blueprint $table) {

            // Drop columns safely
            if (Schema::hasColumn('contributions', 'image_path')) {
                $table->dropColumn('image_path');
            }

            if (Schema::hasColumn('contributions', 'alt_text')) {
                $table->dropColumn('alt_text');
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contributions', function (Blueprint $table) {

            // Recreate columns if rolled back
            $table->string('image_path')->nullable();
            $table->text('alt_text')->nullable();

        });
    }
};