<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->smallInteger('duration_minutes')->nullable();
            $table->smallInteger('buffer_before')->nullable();
            $table->string('buffer_after', 45)->nullable();
            $table->smallInteger('min_notice_hours')->nullable();
            $table->smallInteger('max_notice_days')->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->string('slug', 355);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique(['slug'], 'event_types_slug_uniq');
            $table->index(['user_id'], 'event_types_user_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_types');
    }
};
