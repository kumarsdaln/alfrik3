<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('research_question_findings', function (Blueprint $table) {
            $table->foreignId('question_id')
                ->constrained('research_questions')
                ->cascadeOnDelete();

            $table->foreignId('finding_id')
                ->constrained('research_findings')
                ->cascadeOnDelete();

            $table->unsignedInteger('position')
                ->default(0);

            $table->timestamps();

            $table->primary([
                'question_id',
                'finding_id',
            ]);

            $table->index([
                'question_id',
                'position',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_question_findings');
    }
};
