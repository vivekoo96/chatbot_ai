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
            $table->dateTime('current_period_start')->nullable()->change();
            $table->dateTime('current_period_end')->nullable()->change();
            $table->dateTime('cancelled_at')->nullable()->change();
            $table->dateTime('trial_ends_at')->nullable()->change();
            $table->dateTime('ends_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->timestamp('current_period_start')->nullable()->change();
            $table->timestamp('current_period_end')->nullable()->change();
            $table->timestamp('cancelled_at')->nullable()->change();
            $table->timestamp('trial_ends_at')->nullable()->change();
            $table->timestamp('ends_at')->nullable()->change();
        });
    }
};
