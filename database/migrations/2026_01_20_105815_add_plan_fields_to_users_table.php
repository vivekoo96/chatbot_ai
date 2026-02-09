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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('is_super_admin')->constrained()->nullOnDelete();
            $table->integer('messages_this_month')->default(0)->after('plan_id');
            $table->date('billing_cycle_start')->nullable()->after('messages_this_month');
            $table->string('currency', 3)->default('USD')->after('billing_cycle_start'); // INR or USD
            $table->string('stripe_customer_id')->nullable()->after('currency');
            $table->string('razorpay_customer_id')->nullable()->after('stripe_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'messages_this_month', 'billing_cycle_start', 'currency', 'stripe_customer_id', 'razorpay_customer_id']);
        });
    }
};
