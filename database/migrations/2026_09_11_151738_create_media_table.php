<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            /*
             * Owner of the media.
             *
             * Example:
             * mediable_type = App\Models\Report
             * mediable_id   = 10
             */
            $table->morphs('mediable');

            /*
             * Purpose of the media.
             *
             * Examples:
             * cover
             * thumbnail
             * document
             * gallery
             * attachment
             */
            $table->string('collection', 50)->default('default');

            // Original file information
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type', 100);
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('size');

            // Storage information
            $table->string('disk');
            $table->string('path');

            // Optional information
            $table->string('alt')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index([
                'mediable_type',
                'mediable_id',
                'collection',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};