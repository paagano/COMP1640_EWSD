<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE contributions 
            MODIFY status ENUM(
                'submitted',
                'commented',
                'selected',
                'rejected',
                'published'
            ) NOT NULL DEFAULT 'submitted'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE contributions 
            MODIFY status ENUM(
                'submitted',
                'commented',
                'selected',
                'rejected'
            ) NOT NULL DEFAULT 'submitted'
        ");
    }
};