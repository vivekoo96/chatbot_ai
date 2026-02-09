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
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "+5,000 Messages"
            $table->string('slug')->unique(); // e.g., "messages_5000"
            $table->enum('type', ['messages', 'voice', 'images']); // Add-on type
            $table->integer('quantity'); // Amount provided (5000, 20, 200)
            $table->decimal('price_inr', 10, 2); // Price in INR
            $table->decimal('price_usd', 10, 2); // Price in USD
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Indexes
            $table->index('type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};
