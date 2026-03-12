<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('meeting_participants', function (Blueprint $table) {
            $table->tinyInteger('score')->nullable()->after('excuse'); // 1–5
            $table->text('score_note')->nullable()->after('score');    // catatan penilaian
        });
    }

    public function down(): void
    {
        Schema::table('meeting_participants', function (Blueprint $table) {
            $table->dropColumn(['score', 'score_note']);
        });
    }
};
