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
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('answered_by');
            $table->longText('answer')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index(['answered_by'], 'interview_answers_answered_by_idx');
            $table->index(['question_id'], 'interview_answers_question_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_answers');
    }
};
