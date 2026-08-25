<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->text('meeting_url')->nullable();
            $table->string('location', 255)->nullable();
            $table->index(['event_id'], 'event_sessions_event_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_sessions');
    }
};
