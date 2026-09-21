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
        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('survey_id')
                ->constrained('surveys')
                ->cascadeOnDelete();

            $table->foreignId('section_id')
                ->nullable()
                ->constrained('survey_sections')
                ->nullOnDelete();

            $table->text('question');

            $table->text('description')->nullable();

            $table->string('type', 50);

            $table->boolean('required')->default(false);

            $table->unsignedInteger('position')->default(0);

            $table->json('settings')->nullable();

            $table->timestamps();

            $table->index(['survey_id', 'position']);
            $table->index(['section_id', 'position']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
    }
};
