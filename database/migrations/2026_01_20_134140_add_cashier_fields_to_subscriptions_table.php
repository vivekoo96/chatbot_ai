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
            $table->string('name')->default('default')->after('user_id');
            $table->string('stripe_id')->nullable()->after('gateway_subscription_id');
            $table->string('stripe_status')->nullable()->after('stripe_id');
            $table->string('stripe_price')->nullable()->after('stripe_status');
            $table->integer('quantity')->nullable()->after('stripe_price');
            $table->timestamp('trial_ends_at')->nullable()->after('quantity');
            $table->timestamp('ends_at')->nullable()->after('trial_ends_at');

            // Allow nulls for gateway IDs to support both
            $table->string('payment_gateway')->nullable()->change();
            $table->foreignId('plan_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'name',
                'stripe_id',
                'stripe_status',
                'stripe_price',
                'quantity',
                'trial_ends_at',
                'ends_at',
            ]);
        });
    }
};
