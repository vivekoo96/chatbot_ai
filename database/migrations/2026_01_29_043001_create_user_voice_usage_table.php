<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_voice_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('chatbot_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('duration_seconds')->default(0); // Voice interaction duration
            $table->string('session_id')->nullable(); // Track individual voice sessions
            $table->integer('month')->default(0); // 1-12
            $table->integer('year')->default(0); // e.g., 2026
            $table->timestamps();

            // Index for efficient monthly queries
            $table->index(['user_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_voice_usage');
    }
};
