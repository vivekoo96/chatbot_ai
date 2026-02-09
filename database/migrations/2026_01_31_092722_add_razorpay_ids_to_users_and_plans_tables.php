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
            if (!Schema::hasColumn('users', 'razorpay_customer_id')) {
                $table->string('razorpay_customer_id')->nullable()->after('email');
            }
        });

        Schema::table('plans', function (Blueprint $table) {
            if (!Schema::hasColumn('plans', 'razorpay_plan_id')) {
                $table->string('razorpay_plan_id')->nullable()->after('slug');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('razorpay_customer_id');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('razorpay_plan_id');
        });
    }
};
