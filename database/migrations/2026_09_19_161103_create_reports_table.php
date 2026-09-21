<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('research_id')
                ->nullable()
                ->constrained('researches')
                ->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();

            $table->string('subtitle')->nullable();

            $table->longText('description')->nullable();

            $table->text('summary')->nullable();

            $table->string('type', 50)
                ->default('research')
                ->index();

            $table->string('status', 30)
                ->default('draft')
                ->index();

            $table->foreignId('author_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->boolean('featured')
                ->default(false)
                ->index();

            $table->timestamp('published_at')
                ->nullable()
                ->index();

            $table->timestamp('report_date')
                ->nullable()
                ->index();

            $table->timestamps();

            $table->index([
                'research_id',
                'status',
            ]);

            $table->index([
                'status',
                'published_at',
            ]);

            $table->index([
                'author_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};