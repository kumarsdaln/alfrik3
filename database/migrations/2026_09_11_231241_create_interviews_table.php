<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            // Short editorial summary / excerpt
            $table->text('description')->nullable();

            // Editorial content before and after the conversation
            $table->longText('introduction')->nullable();
            $table->longText('conclusion')->nullable();

            $table->string('interview_type', 30)
                ->default('written');

            $table->string('status', 30)
                ->default('draft');

            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};