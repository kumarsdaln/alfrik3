<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
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
            | Ticket
            |--------------------------------------------------------------------------
            */

            $table->foreignId('ticket_id')
                ->constrained('event_tickets')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Attendee
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Registration
            |--------------------------------------------------------------------------
            */

            $table->string('registration_code', 100)->unique();

            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'refunded',
            ])->default('confirmed');

            /*
            |--------------------------------------------------------------------------
            | QR Code
            |--------------------------------------------------------------------------
            */

            $table->string('qr_code', 255)->nullable()->unique();

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'event_id',
                'status',
            ]);

            $table->index([
                'user_id',
                'event_id',
            ]);

            $table->index('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};