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

            /*
            |--------------------------------------------------------------------------
            | Relationship
            |--------------------------------------------------------------------------
            */

            $table->foreignId('magazine_id')
                ->constrained('magazines')
                ->cascadeOnDelete();

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
            | Issue Information
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('volume')->nullable();
            $table->unsignedInteger('issue_number')->nullable();

            $table->date('cover_date')->nullable();
            $table->dateTime('published_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Editorial
            |--------------------------------------------------------------------------
            */

            $table->text('description')->nullable();
            $table->string('editor')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Cover
            |--------------------------------------------------------------------------
            */

            $table->string('cover_image')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Digital Edition
            |--------------------------------------------------------------------------
            */

            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('file_type', 20)->nullable();

            $table->unsignedBigInteger('download_count')->default(0);

            /*
            |--------------------------------------------------------------------------
            | Publishing
            |--------------------------------------------------------------------------
            */

            $table->boolean('status')->default(false);
            $table->boolean('featured')->default(false);

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

            $table->index(['magazine_id', 'published_at']);
            $table->index(['magazine_id', 'volume', 'issue_number']);
            $table->index(['status', 'published_at']);
            $table->index('featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magazine_issues');
    }
};