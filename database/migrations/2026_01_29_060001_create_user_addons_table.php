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
        Schema::create('user_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('addon_id')->constrained()->onDelete('cascade');

            // Quantity tracking
            $table->integer('quantity_total'); // Total purchased
            $table->integer('quantity_used')->default(0); // Amount consumed
            $table->integer('quantity_remaining'); // Amount left

            // Payment and status
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_order_id')->nullable();
            $table->decimal('amount_paid', 10, 2);
            $table->enum('status', ['active', 'expired', 'consumed'])->default('active');

            // Dates
            $table->timestamp('purchased_at');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addons');
    }
};
