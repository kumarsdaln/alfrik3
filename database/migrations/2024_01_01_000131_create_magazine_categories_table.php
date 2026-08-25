<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magazine_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 245);
            $table->string('icon', 245)->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title', 100)->nullable();
            $table->string('meta_description', 300)->nullable();
            $table->string('meta_keywords', 300)->nullable();
            $table->string('slug', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magazine_categories');
    }
};
