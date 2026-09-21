<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('research_sources', function (Blueprint $table) {
            $table->id();

            $table->foreignId('research_id')
                ->constrained('researches')
                ->cascadeOnDelete();

            $table->string('title');

            $table->string('source_type', 50);

            $table->string('author')->nullable();

            $table->string('publisher')->nullable();

            $table->string('url')->nullable();

            $table->date('published_at')->nullable();

            $table->text('citation')->nullable();

            $table->text('description')->nullable();

            $table->unsignedInteger('position')->default(0);

            $table->timestamps();

            $table->index(['research_id', 'position']);
            $table->index('source_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_sources');
    }
};
