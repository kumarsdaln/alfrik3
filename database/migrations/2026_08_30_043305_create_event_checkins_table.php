<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_checkins', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Registration
            |--------------------------------------------------------------------------
            */

            $table->foreignId('registration_id')
                ->constrained('event_registrations')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Staff Member
            |--------------------------------------------------------------------------
            */

            $table->foreignId('checked_in_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Check-in Information
            |--------------------------------------------------------------------------
            */

            $table->dateTime('checked_in_at');

            $table->enum('type', [
                'entry',
                'exit',
            ])->default('entry');

            /*
            |--------------------------------------------------------------------------
            | Location / Device
            |--------------------------------------------------------------------------
            */

            $table->string('gate', 100)->nullable();
            $table->string('device', 255)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Result
            |--------------------------------------------------------------------------
            */

            $table->boolean('successful')->default(true);

            $table->text('notes')->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'registration_id',
                'checked_in_at',
            ]);

            $table->index([
                'registration_id',
                'type',
            ]);

            $table->index('checked_in_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_checkins');
    }
};