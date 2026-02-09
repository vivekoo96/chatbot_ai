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
        Schema::table('subscriptions', function (Blueprint $table) {
            // Razorpay subscription specific fields
            $table->string('razorpay_subscription_id')->nullable()->after('gateway_subscription_id');
            $table->string('razorpay_plan_id')->nullable()->after('razorpay_subscription_id');
            $table->string('razorpay_customer_id')->nullable()->after('razorpay_plan_id');

            // Billing and renewal tracking
            $table->timestamp('next_billing_at')->nullable()->after('current_period_end');

            // Failed payment tracking
            $table->integer('failed_payment_count')->default(0)->after('next_billing_at');
            $table->timestamp('grace_period_ends_at')->nullable()->after('failed_payment_count');

            // Auto-renewal control
            $table->boolean('auto_renew')->default(true)->after('grace_period_ends_at');

            // Cancellation tracking
            $table->boolean('cancel_at_period_end')->default(false)->after('auto_renew');

            // Add index for efficient queries
            $table->index('razorpay_subscription_id');
            $table->index('next_billing_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex(['razorpay_subscription_id']);
            $table->dropIndex(['next_billing_at']);

            $table->dropColumn([
                'razorpay_subscription_id',
                'razorpay_plan_id',
                'razorpay_customer_id',
                'next_billing_at',
                'failed_payment_count',
                'grace_period_ends_at',
                'auto_renew',
                'cancel_at_period_end',
            ]);
        });
    }
};
