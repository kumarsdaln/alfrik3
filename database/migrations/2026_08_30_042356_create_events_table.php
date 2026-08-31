<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Event Format
            |--------------------------------------------------------------------------
            */

            $table->enum('event_type', [
                'online',
                'offline',
                'hybrid',
            ])->default('offline');

            $table->enum('visibility', [
                'public',
                'private',
            ])->default('public');

            /*
            |--------------------------------------------------------------------------
            | Schedule
            |--------------------------------------------------------------------------
            */

            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Physical Location
            |--------------------------------------------------------------------------
            */

            $table->string('location_name', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 150)->nullable();
            $table->string('state', 150)->nullable();
            $table->string('country', 150)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Online Location
            |--------------------------------------------------------------------------
            */

            $table->text('meeting_url')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            $table->string('banner', 255)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Capacity
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('max_attendees')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Publishing
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'draft',
                'published',
                'cancelled',
            ])->default('draft');

            /*
            |--------------------------------------------------------------------------
            | Creator
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('start_date');
            $table->index('status');
            $table->index('visibility');
            $table->index('event_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};