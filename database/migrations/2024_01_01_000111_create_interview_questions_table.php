<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interview_id');
            $table->unsignedBigInteger('asked_by')->nullable();
            $table->text('question');
            $table->integer('order')->nullable()->default('0');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index(['asked_by'], 'interview_questions_asked_by_idx');
            $table->index(['interview_id'], 'interview_questions_interview_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_questions');
    }
};
