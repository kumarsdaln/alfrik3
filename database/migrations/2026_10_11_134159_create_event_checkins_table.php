
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_checkins', function (Blueprint $table) {
            $table->id();

            $table->foreignId('registration_id')
                ->constrained('event_registrations')
                ->cascadeOnDelete();

            $table->foreignId('checked_in_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Check-in record
            $table->dateTime('checked_in_at');

            $table->enum('type', [
                'entry',
                'exit',
            ])->default('entry');

            // Check-in details
            $table->string('gate', 100)->nullable();
            $table->string('device', 255)->nullable();

            $table->boolean('successful')->default(true);
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index([
                'registration_id',
                'checked_in_at',
            ]);

            $table->index([
                'registration_id',
                'type',
            ]);

            $table->index('checked_in_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_checkins');
    }
};
