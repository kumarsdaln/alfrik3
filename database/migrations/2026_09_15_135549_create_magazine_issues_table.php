<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magazine_issues', function (Blueprint $table) {
            $table->id();

            // Parent publication
            $table->foreignId('magazine_id')
                ->constrained('magazines')
                ->cascadeOnDelete();

            // Identity
            $table->string('title');
            $table->string('slug');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();

            // Edition information
            $table->unsignedInteger('volume')->nullable();
            $table->unsignedInteger('issue_number')->nullable();
            $table->date('cover_date')->nullable();

            // Publishing
            $table->string('status', 30)->default('draft');
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            // A slug is unique within its magazine
            $table->unique(
                ['magazine_id', 'slug'],
                'magazine_issues_magazine_slug_unique'
            );

            // A numbered edition should not be duplicated
            $table->unique(
                ['magazine_id', 'volume', 'issue_number'],
                'magazine_issues_edition_unique'
            );

            // Magazine archive
            $table->index(['magazine_id', 'published_at']);

            // Public listing
            $table->index(['status', 'published_at']);

            // Featured issue listings
            $table->index(['featured', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magazine_issues');
    }
};