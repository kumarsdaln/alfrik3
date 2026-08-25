<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_category_map', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->index(['event_id'], 'event_category_map_event_id_idx');
            $table->index(['category_id'], 'event_category_map_category_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_category_map');
    }
};
