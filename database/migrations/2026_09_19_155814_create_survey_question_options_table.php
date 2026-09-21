<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_question_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('question_id')
                ->constrained('survey_questions')
                ->cascadeOnDelete();

            $table->string('label');

            $table->string('value');

            $table->unsignedInteger('position')->default(0);

            $table->boolean('is_other')->default(false);

            $table->timestamps();

            $table->index([
                'question_id',
                'position',
            ]);

            $table->index([
                'question_id',
                'value',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_question_options');
    }
};