<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magazine_articles', function (Blueprint $table) {
            $table->id();

            // Parent issue
            $table->foreignId('issue_id')
                ->constrained('magazine_issues')
                ->cascadeOnDelete();

            // Identity
            $table->string('title');
            $table->string('slug');
            $table->string('subtitle')->nullable();

            // Editorial content
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();

            // Article classification
            $table->string('type', 50)->default('article');

            // Ordering within an issue
            $table->unsignedInteger('position')->default(0);

            // Publishing
            $table->string('status', 30)->default('draft');
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            // Same slug may appear in different issues
            $table->unique(
                ['issue_id', 'slug'],
                'magazine_articles_issue_slug_unique'
            );

            // Table of contents
            $table->index(['issue_id', 'position']);

            // Public article listings
            $table->index(['status', 'published_at']);

            // Issue-specific published articles
            $table->index(['issue_id', 'status', 'position']);

            // Article type filtering
            $table->index(['type', 'status']);

            // Featured article listings
            $table->index(['featured', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magazine_articles');
    }
};