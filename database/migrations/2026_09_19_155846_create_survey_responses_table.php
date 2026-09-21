<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('survey_id')
                ->constrained('surveys')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('respondent_name')->nullable();

            $table->string('respondent_email')->nullable();

            $table->ipAddress('respondent_ip')->nullable();

            $table->text('user_agent')->nullable();

            $table->timestamp('started_at')->nullable();

            $table->timestamp('submitted_at')->nullable();

            $table->string('status', 30)
                ->default('in_progress')
                ->index();

            $table->timestamps();

            $table->index([
                'survey_id',
                'status',
            ]);

            $table->index([
                'survey_id',
                'submitted_at',
            ]);

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};