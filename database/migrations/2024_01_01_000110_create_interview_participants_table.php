<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interview_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('role', ['interviewer', 'interviewee']);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->index(['user_id'], 'interview_participants_user_id_idx');
            $table->index(['interview_id'], 'interview_participants_interview_id_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_participants');
    }
};
