<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_tickets', function (Blueprint $table) {
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
            | Ticket Information
            |--------------------------------------------------------------------------
            */

            $table->string('name', 255);
            $table->string('slug', 255);

            $table->text('description')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            $table->decimal('price', 12, 2)->default(0);
            $table->char('currency', 3)->default('USD');

            /*
            |--------------------------------------------------------------------------
            | Capacity
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('capacity')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Sales Window
            |--------------------------------------------------------------------------
            */

            $table->dateTime('sales_start')->nullable();
            $table->dateTime('sales_end')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Visibility / Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_visible')->default(true);
            $table->boolean('is_active')->default(true);

            /*
            |--------------------------------------------------------------------------
            | Ordering
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('position')->default(0);

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Constraints & Indexes
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'event_id',
                'slug',
            ]);

            $table->index([
                'event_id',
                'is_active',
                'is_visible',
            ]);

            $table->index([
                'event_id',
                'position',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_tickets');
    }
};