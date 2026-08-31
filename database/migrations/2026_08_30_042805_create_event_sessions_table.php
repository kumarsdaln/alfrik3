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

            /*
            |--------------------------------------------------------------------------
            | Event
            |--------------------------------------------------------------------------
            */

            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Session Information
            |--------------------------------------------------------------------------
            */

            $table->string('title', 255);

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Schedule
            |--------------------------------------------------------------------------
            */

            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            $table->string('location', 255)->nullable();

            $table->text('meeting_url')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Ordering
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('position')->default(0);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'event_id',
                'start_time',
            ]);

            $table->index([
                'event_id',
                'position',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_sessions');
    }
};