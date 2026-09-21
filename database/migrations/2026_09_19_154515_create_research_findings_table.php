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
        Schema::create('research_findings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('research_id')
                ->constrained('researches')
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('summary')->nullable();

            $table->longText('description')->nullable();

            $table->string('type', 50)->default('insight');

            $table->decimal('confidence', 5, 2)->nullable();

            $table->unsignedInteger('position')->default(0);

            $table->timestamps();

            $table->index(['research_id', 'position']);
            $table->index(['research_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_findings');
    }
};
