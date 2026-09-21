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
        Schema::create('research_methodologies', function (Blueprint $table) {
            $table->id();

            $table->foreignId('research_id')
                ->constrained('researches')
                ->cascadeOnDelete();

            $table->string('method', 100);

            $table->text('description')->nullable();

            $table->string('research_design', 100)->nullable();

            $table->string('data_collection_method', 100)->nullable();

            $table->unsignedInteger('sample_size')->nullable();

            $table->text('population')->nullable();

            $table->text('geography')->nullable();

            $table->date('start_date')->nullable();

            $table->date('end_date')->nullable();

            $table->text('limitations')->nullable();

            $table->timestamps();

            $table->unique('research_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_methodologies');
    }
};
