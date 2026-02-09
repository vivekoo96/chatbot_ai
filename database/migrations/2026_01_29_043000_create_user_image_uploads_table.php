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
        Schema::create('user_image_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('chatbot_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->integer('file_size')->default(0); // in bytes
            $table->string('mime_type')->nullable();
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
        Schema::dropIfExists('user_image_uploads');
    }
};
