<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 150);
            $table->string('slug', 150);

            $table->timestamps();

            $table->unique('name', 'positions_name_uniq');
            $table->unique('slug', 'positions_slug_uniq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};