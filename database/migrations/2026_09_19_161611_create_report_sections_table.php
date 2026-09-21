<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_sections', function (Blueprint $table) {
            $table->id();

            $table->foreignId('report_id')
                ->constrained('reports')
                ->cascadeOnDelete();

            $table->string('title');

            $table->text('subtitle')->nullable();

            $table->longText('content')->nullable();

            $table->unsignedInteger('position')->default(0);

            $table->timestamps();

            $table->index([
                'report_id',
                'position',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_sections');
    }
};