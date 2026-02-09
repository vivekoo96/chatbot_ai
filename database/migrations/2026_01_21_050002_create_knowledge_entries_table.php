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
        Schema::create('knowledge_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chatbot_id')->constrained()->onDelete('cascade');
            $table->foreignId('website_page_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('content'); // Chunked content for RAG
            $table->string('source_url')->nullable();
            $table->string('category')->nullable(); // 'product', 'service', 'pricing', 'about'
            $table->integer('usage_count')->default(0); // Track which entries are most useful
            $table->timestamps();

            $table->index('chatbot_id');
            $table->fullText('content'); // For fast text search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_entries');
    }
};
