<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->nullable();
            $table->text('description')->nullable();
            $table->enum('interview_type', ['written', 'video', 'audio', 'mixed'])->nullable()->default('written');
            $table->enum('status', ['draft', 'published'])->nullable()->default('draft');
            $table->string('thumbnail', 255)->nullable();
            $table->integer('duration')->nullable();
            $table->bigInteger('views_count')->nullable()->default('0');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique(['slug'], 'interviews_slug_uniq');
            $table->index(['created_by'], 'interviews_created_by_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
