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
        Schema::create('research_evidence', function (Blueprint $table) {
            $table->id();

            $table->foreignId('research_id')
                ->constrained('researches')
                ->cascadeOnDelete();

            $table->foreignId('finding_id')
                ->constrained('research_findings')
                ->cascadeOnDelete();

            $table->string('type', 50);

            $table->unsignedBigInteger('reference_id')
                ->nullable();

            $table->text('title')->nullable();

            $table->text('description')->nullable();

            $table->text('citation')->nullable();

            $table->unsignedInteger('position')
                ->default(0);

            $table->timestamps();

            $table->index([
                'finding_id',
                'type',
                'reference_id',
            ]);

            $table->index([
                'research_id',
                'position',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_evidence');
    }
};
