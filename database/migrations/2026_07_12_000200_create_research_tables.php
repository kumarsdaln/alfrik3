<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Research module: academic-style research papers/studies with abstracts,
 * authorship, disciplines, methodology, DOI and citations.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 160)->unique();
            $table->text('description')->nullable();
        });

        Schema::create('research_papers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('abstract')->nullable();
            $table->string('authors')->nullable();        // free-form author list
            $table->unsignedBigInteger('author_id')->nullable()->index(); // platform publisher
            $table->unsignedBigInteger('area_id')->nullable()->index();   // discipline
            $table->string('institution')->nullable();
            $table->text('methodology')->nullable();
            $table->string('doi')->nullable();
            $table->text('citation')->nullable();
            $table->string('keywords', 500)->nullable();
            $table->string('cover_image')->nullable();
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('file_type', 20)->nullable();
            $table->date('published_at')->nullable()->index();
            $table->boolean('status')->default(false);
            $table->boolean('featured')->default(false);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_papers');
        Schema::dropIfExists('research_areas');
    }
};
