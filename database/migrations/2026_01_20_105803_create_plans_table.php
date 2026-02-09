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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Free, Starter, Pro, Business
            $table->string('slug')->unique(); // free, starter, pro, business
            $table->decimal('price_inr', 10, 2)->default(0); // Price in INR
            $table->decimal('price_usd', 10, 2)->default(0); // Price in USD

            // Core Limits
            $table->integer('max_chatbots')->default(1);
            $table->integer('max_messages_per_month')->default(100);

            // New Feature Limits
            $table->integer('max_image_uploads_per_month')->default(0); // Monthly image upload limit
            $table->integer('max_voice_minutes_per_month')->default(0); // Monthly voice minutes limit
            $table->integer('max_team_users')->default(1); // Number of team members allowed

            // Feature Flags
            $table->boolean('has_api_access')->default(false); // API access permission
            $table->boolean('has_analytics_dashboard')->default(false); // Analytics dashboard access
            $table->boolean('has_advanced_analytics')->default(false); // Advanced analytics access
            $table->boolean('has_branding_removal')->default(false); // Remove Hemnix branding option
            $table->boolean('has_lead_capture')->default(false); // Lead capture feature
            $table->boolean('has_custom_ai_guidance')->default(false); // Custom AI guidance
            $table->boolean('has_advanced_rules')->default(false); // Advanced rules & guidance

            // Additional Settings
            $table->integer('chat_history_days')->default(7); // Chat history retention period
            $table->string('support_level')->default('email'); // email, priority, dedicated

            $table->json('features')->nullable(); // Additional features as JSON
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0); // For display ordering
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
