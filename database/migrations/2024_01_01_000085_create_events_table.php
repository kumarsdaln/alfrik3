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
            $table->string('title', 255);
            $table->string('slug', 255)->nullable();
            $table->longText('description')->nullable();
            $table->enum('event_type', ['online', 'offline', 'hybrid'])->nullable()->default('offline');
            $table->enum('visibility', ['public', 'private'])->nullable()->default('public');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('location_name', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->text('meeting_url')->nullable();
            $table->string('banner', 255)->nullable();
            $table->integer('max_attendees')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled'])->nullable()->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique(['slug'], 'events_slug_uniq');
            $table->index(['created_by'], 'events_created_by_idx');
            $table->index(['start_date'], 'events_start_date_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
