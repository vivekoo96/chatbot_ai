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
        Schema::table('chatbots', function (Blueprint $table) {
            $table->string('detected_website_url', 500)->nullable()->after('system_prompt');
            $table->timestamp('last_crawled_at')->nullable();
            $table->integer('pages_crawled')->default(0);
            $table->boolean('auto_crawl_enabled')->default(true);
            $table->enum('crawl_status', ['pending', 'crawling', 'completed', 'failed'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatbots', function (Blueprint $table) {
            $table->dropColumn([
                'detected_website_url',
                'last_crawled_at',
                'pages_crawled',
                'auto_crawl_enabled',
                'crawl_status'
            ]);
        });
    }
};
