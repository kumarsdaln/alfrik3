<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('researches', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->string('subtitle')->nullable();

            $table->longText('description')->nullable();
            $table->text('summary')->nullable();

            $table->string('type', 50)
                ->default('general')
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

            $table->timestamps();

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
        Schema::dropIfExists('researches');
    }
};