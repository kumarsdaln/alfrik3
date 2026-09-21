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
        Schema::create('report_versions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('report_id')
                ->constrained('reports')
                ->cascadeOnDelete();

            $table->unsignedInteger('version');

            $table->string('title');
            $table->string('subtitle')->nullable();

            $table->longText('description')->nullable();
            $table->text('summary')->nullable();

            $table->string('status', 30)
                ->default('draft')
                ->index();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('published_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('published_at')->nullable();

            $table->text('change_summary')->nullable();

            $table->timestamps();

            $table->unique([
                'report_id',
                'version',
            ]);

            $table->index([
                'report_id',
                'status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_versions');
    }
};
