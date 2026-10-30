
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
        Schema::create('content_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('participant_role_id')
                ->constrained('participant_roles')
                ->restrictOnDelete();

            // Polymorphic relationship to research, interviews,
            // reports, magazines, surveys, events, etc.
            $table->morphs('participantable');

            // Display ordering and primary participant designation.
            $table->unsignedInteger('position')->default(0);
            $table->boolean('is_primary')->default(false);

            // Optional contribution details.
            $table->text('contribution')->nullable();

            $table->timestamps();

            // A user can participate only once per content item.
            $table->unique(
                [
                    'participantable_type',
                    'participantable_id',
                    'user_id',
                ],
                'content_participants_unique_user_content'
            );

            $table->index(
                ['user_id', 'participant_role_id'],
                'content_participants_user_role_index'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_participants');
    }
};
