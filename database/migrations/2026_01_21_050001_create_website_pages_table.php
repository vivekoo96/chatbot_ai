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
        Schema::create('website_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chatbot_id')->constrained()->onDelete('cascade');
            $table->string('url', 500)->unique();
            $table->string('title')->nullable();
            $table->text('content'); // Extracted text content
            $table->text('meta_description')->nullable();
            $table->json('headings')->nullable(); // H1, H2, H3 for better context
            $table->timestamp('last_crawled_at');
            $table->timestamps();

            $table->index(['chatbot_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_pages');
    }
};
