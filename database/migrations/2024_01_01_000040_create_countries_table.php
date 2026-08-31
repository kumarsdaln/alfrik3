<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 100);
            $table->string('code', 2)->unique();
            $table->string('native', 100);
            $table->string('phone', 15);
            $table->string('continent_code', 2);
            $table->string('capital', 100);
            $table->string('currency', 31);
            $table->string('languages', 100);

            $table->foreign('continent_code')
                ->references('code')
                ->on('continents')
                ->restrictOnDelete();

            $table->index('continent_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
