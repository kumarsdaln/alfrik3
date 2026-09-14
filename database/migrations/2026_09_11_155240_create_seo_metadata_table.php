<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_metadata', function (Blueprint $table) {
            $table->id();

            /*
             * Entity/page this SEO metadata belongs to.
             *
             * Example:
             * seoable_type = App\Models\Report
             * seoable_id   = 10
             */
            $table->morphs('seoable');

            /*
             * Search engine metadata
             */
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();

            /*
             * Canonical URL
             */
            $table->string('canonical_url', 2048)->nullable();

            /*
             * Indexing directives
             */
            $table->boolean('indexable')->default(true);
            $table->boolean('followable')->default(true);

            /*
             * Open Graph
             */
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_type', 50)->nullable();
            $table->string('og_image_url', 2048)->nullable();

            /*
             * X / Twitter
             */
            $table->string('twitter_card', 50)->nullable();
            $table->string('twitter_title', 255)->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image_url', 2048)->nullable();

            /*
             * Language / locale
             */
            $table->string('locale', 20)->default('en');

            /*
             * Structured-data type.
             *
             * Examples:
             * WebPage
             * Article
             * Report
             * ScholarlyArticle
             * Person
             */
            $table->string('schema_type', 100)->nullable();

            $table->timestamps();

            /*
             * One SEO record per entity per locale.
             */
            $table->unique([
                'seoable_type',
                'seoable_id',
                'locale',
            ]);

            $table->index('indexable');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metadata');
    }
};