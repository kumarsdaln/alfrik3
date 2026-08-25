<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Surveys module: builder (surveys → questions → options) and response
 * collection (responses → answers) for aggregated results.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(false);          // published toggle
            $table->timestamp('published_at')->nullable();
            $table->timestamp('closes_at')->nullable();          // auto-close date
            $table->boolean('allow_anonymous')->default(true);   // guests may respond
            $table->boolean('one_response_per_user')->default(true);
            $table->boolean('show_results')->default(false);     // expose public results
            $table->unsignedBigInteger('author_id')->nullable()->index();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id')->index();
            $table->text('question');
            // single_choice | multiple_choice | text | rating
            $table->string('type', 30)->default('single_choice');
            $table->boolean('required')->default(false);
            $table->unsignedInteger('position')->default(0);
            $table->json('settings')->nullable();                // e.g. {"max":5} for rating
            $table->timestamps();
        });

        Schema::create('survey_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id')->index();
            $table->string('label');
            $table->unsignedInteger('position')->default(0);
        });

        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_token', 64)->nullable()->index(); // anonymous dedup
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
        });

        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('response_id')->index();
            $table->unsignedBigInteger('question_id')->index();
            $table->unsignedBigInteger('option_id')->nullable()->index(); // choice answers
            $table->text('value_text')->nullable();               // text / rating value
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('survey_options');
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('surveys');
    }
};
