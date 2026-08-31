<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_speakers', function (Blueprint $table) {
            $table->foreignId('session_id')
                ->constrained('event_sessions')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->unsignedInteger('position')->default(0);

            $table->unique([
                'session_id',
                'user_id',
            ]);

            $table->index([
                'session_id',
                'position',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_speakers');
    }
};