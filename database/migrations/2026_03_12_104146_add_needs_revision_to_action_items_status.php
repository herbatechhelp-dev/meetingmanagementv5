<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Altering ENUM requires raw SQL statement or doctrine/dbal. 
        // Using raw SQL is safer for ENUM appending.
        DB::statement("ALTER TABLE action_items MODIFY COLUMN status ENUM('pending', 'in_progress', 'waiting_review', 'needs_revision', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-altering the table to back down the ENUM value. 
        // Note: this will crash if any records actually have the 'needs_revision' value 
        // but it's correct for a down function schema roll-back.
        DB::statement("ALTER TABLE action_items MODIFY COLUMN status ENUM('pending', 'in_progress', 'waiting_review', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
