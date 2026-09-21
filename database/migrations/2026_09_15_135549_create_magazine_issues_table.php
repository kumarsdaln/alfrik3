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
            $table->timestamp('published_at')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Editorial
            |--------------------------------------------------------------------------
            */

            $table->text('description')->nullable();
            $table->string('editor')->nullable();

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

            $table->string('status', 30)->default('draft')->index();
            $table->boolean('featured')->default(false)->index();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index(['magazine_id', 'published_at']);
            $table->index([
                'magazine_id',
                'volume',
                'issue_number',
            ]);
            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magazine_issues');
    }
};