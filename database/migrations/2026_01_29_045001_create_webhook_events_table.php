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
        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->unique(); // Razorpay event ID
            $table->string('event_type'); // subscription.activated, invoice.paid, etc.
            $table->string('entity_type')->nullable(); // subscription, invoice, etc.
            $table->string('entity_id')->nullable(); // ID of the entity
            $table->json('payload'); // Full webhook payload
            $table->enum('status', ['pending', 'processing', 'processed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            // Indexes for efficient queries
            $table->index('event_type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
    }
};
