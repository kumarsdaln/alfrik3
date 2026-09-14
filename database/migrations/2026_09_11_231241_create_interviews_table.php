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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();

            $table->text('description')->nullable();

            $table->string('interview_type', 30)
                ->default('written');

            $table->string('status', 30)
                ->default('draft');

            $table->timestamp('published_at')->nullable();

            $table->unsignedBigInteger('created_by')
                ->nullable()
                ->index();

            $table->unsignedBigInteger('views_count')
                ->default(0);

            $table->timestamps();

            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
