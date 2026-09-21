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
        Schema::create('report_findings', function (Blueprint $table) {
            $table->foreignId('report_id')
                ->constrained('reports')
                ->cascadeOnDelete();

            $table->foreignId('finding_id')
                ->constrained('research_findings')
                ->cascadeOnDelete();

            $table->unsignedInteger('position')
                ->default(0);

            $table->timestamps();

            $table->primary(['report_id', 'finding_id']);

            $table->index(['report_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_findings');
    }
};
