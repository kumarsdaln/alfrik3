<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_content_blocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('report_section_id')
                ->constrained('report_sections')
                ->cascadeOnDelete();

            $table->string('type', 50);

            $table->string('title')->nullable();

            $table->text('description')->nullable();

            $table->json('content')->nullable();

            $table->unsignedInteger('position')->default(0);

            $table->timestamps();

            $table->index([
                'report_section_id',
                'position',
            ]);

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_content_blocks');
    }
};
