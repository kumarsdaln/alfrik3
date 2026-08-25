<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interview_id');
            $table->enum('media_type', ['video', 'audio']);
            $table->enum('source_type', ['upload', 'external'])->nullable()->default('external');
            $table->string('file_url', 255)->nullable();
            $table->text('embed_url')->nullable();
            $table->integer('duration')->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->index(['interview_id'], 'interview_media_interview_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_media');
    }
};
