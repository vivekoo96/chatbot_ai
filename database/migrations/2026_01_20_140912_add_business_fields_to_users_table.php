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
        Schema::table('users', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('business_name')->nullable()->after('name');
            $table->string('industry')->nullable()->after('business_name');
            $table->string('country')->nullable()->after('industry');
            $table->string('business_size')->nullable()->after('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn(['business_name', 'industry', 'country', 'business_size']);
        });
    }
};
