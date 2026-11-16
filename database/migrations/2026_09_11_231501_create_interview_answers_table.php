<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_id')
                ->constrained('interview_questions')
                ->cascadeOnDelete();

            $table->foreignId('answered_by')
                ->nullable()
                ->constrained('content_participants')
                ->nullOnDelete();

            $table->longText('answer');

            $table->unsignedInteger('position')->default(0);

            $table->timestamps();

            $table->index(['question_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_answers');
    }
};