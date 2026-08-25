<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reports module: downloadable industry/insight reports with a topic taxonomy.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 160)->unique();
            $table->text('description')->nullable();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('file_path')->nullable();      // downloadable document (PDF)
            $table->unsignedBigInteger('file_size')->nullable(); // bytes
            $table->string('file_type', 20)->nullable();  // e.g. pdf
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('author_id')->nullable()->index();
            $table->year('report_year')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->boolean('status')->default(false);    // published toggle
            $table->boolean('featured')->default(false);
            $table->boolean('gated')->default(false);     // require login to download
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
        Schema::dropIfExists('reports');
        Schema::dropIfExists('report_categories');
    }
};
