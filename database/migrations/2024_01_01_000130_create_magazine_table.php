<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magazine', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('cover_image', 255)->nullable();
            $table->text('subtitle')->nullable();
            $table->text('content')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->string('slug', 255);
            $table->integer('category_id')->nullable();
            $table->tinyInteger('status')->default('1');
            $table->index(['category_id'], 'magazine_category_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magazine');
    }
};
