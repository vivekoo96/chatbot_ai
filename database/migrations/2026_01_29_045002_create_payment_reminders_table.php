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
        Schema::create('payment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['upcoming_payment', 'payment_failed', 'grace_period_warning', 'subscription_cancelled']);
            $table->timestamp('sent_at')->nullable();
            $table->boolean('is_sent')->default(false);
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'type']);
            $table->index('is_sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_reminders');
    }
};
