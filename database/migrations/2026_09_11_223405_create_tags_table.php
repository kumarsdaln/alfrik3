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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();

            $table->string('name', 150);
            $table->string('slug', 160)->unique();
            $table->text('description')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->index('status');
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignId('tag_id')
                ->constrained('tags')
                ->cascadeOnDelete();

            $table->morphs('taggable');

            $table->primary([
                'tag_id',
                'taggable_type',
                'taggable_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
    }
};