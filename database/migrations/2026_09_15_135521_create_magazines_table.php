<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magazines', function (Blueprint $table) {
            $table->id();

            // Identity
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->longText('description')->nullable();

            // Publishing
            $table->string('status', 30)->default('draft');
            $table->boolean('featured')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            // Listing and publication queries
            $table->index(['status', 'published_at']);
            $table->index(['featured', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magazines');
    }
};