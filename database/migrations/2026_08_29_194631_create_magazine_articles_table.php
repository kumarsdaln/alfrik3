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

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('issue_id')
                ->constrained('magazine_issues')
                ->cascadeOnDelete();

            $table->foreignId('author_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Identity
            |--------------------------------------------------------------------------
            */

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Content
            |--------------------------------------------------------------------------
            */

            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Media
            |--------------------------------------------------------------------------
            */

            $table->string('cover_image')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Editorial
            |--------------------------------------------------------------------------
            */

            $table->string('type', 50)->default('article');
            $table->string('byline')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Ordering
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('position')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Featured
            |--------------------------------------------------------------------------
            */

            $table->boolean('featured')->default(false);

            /*
            |--------------------------------------------------------------------------
            | Publishing
            |--------------------------------------------------------------------------
            */

            $table->boolean('status')->default(false);
            $table->dateTime('published_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Engagement
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('views')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Reading Information
            |--------------------------------------------------------------------------
            */

            $table->unsignedSmallInteger('reading_time')->nullable();

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index(['issue_id', 'position']);
            $table->index(['status', 'published_at']);
            $table->index('featured');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magazine_articles');
    }
};