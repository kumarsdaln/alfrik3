
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_id')
                ->constrained('events')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Rating from 1 to 5
            $table->unsignedTinyInteger('rating');

            // Written feedback
            $table->text('review')->nullable();

            $table->timestamps();

            // One review per user per event
            $table->unique([
                'event_id',
                'user_id',
            ]);

            $table->index([
                'event_id',
                'rating',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_reviews');
    }
};
