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
        Schema::table('plans', function (Blueprint $table) {
            // New Feature Limits
            if (!Schema::hasColumn('plans', 'max_image_uploads_per_month')) {
                $table->integer('max_image_uploads_per_month')->default(0)->after('max_messages_per_month');
            }
            if (!Schema::hasColumn('plans', 'max_voice_minutes_per_month')) {
                $table->integer('max_voice_minutes_per_month')->default(0)->after('max_image_uploads_per_month');
            }
            if (!Schema::hasColumn('plans', 'max_team_users')) {
                $table->integer('max_team_users')->default(1)->after('max_voice_minutes_per_month');
            }

            // Feature Flags
            if (!Schema::hasColumn('plans', 'has_api_access')) {
                $table->boolean('has_api_access')->default(false)->after('max_team_users');
            }
            if (!Schema::hasColumn('plans', 'has_analytics_dashboard')) {
                $table->boolean('has_analytics_dashboard')->default(false)->after('has_api_access');
            }
            if (!Schema::hasColumn('plans', 'has_advanced_analytics')) {
                $table->boolean('has_advanced_analytics')->default(false)->after('has_analytics_dashboard');
            }
            if (!Schema::hasColumn('plans', 'has_branding_removal')) {
                $table->boolean('has_branding_removal')->default(false)->after('has_advanced_analytics');
            }
            if (!Schema::hasColumn('plans', 'has_lead_capture')) {
                $table->boolean('has_lead_capture')->default(false)->after('has_branding_removal');
            }
            if (!Schema::hasColumn('plans', 'has_custom_ai_guidance')) {
                $table->boolean('has_custom_ai_guidance')->default(false)->after('has_lead_capture');
            }
            if (!Schema::hasColumn('plans', 'has_advanced_rules')) {
                $table->boolean('has_advanced_rules')->default(false)->after('has_custom_ai_guidance');
            }

            // Additional Settings
            if (!Schema::hasColumn('plans', 'chat_history_days')) {
                $table->integer('chat_history_days')->default(7)->after('has_advanced_rules');
            }
            if (!Schema::hasColumn('plans', 'support_level')) {
                $table->string('support_level')->default('email')->after('chat_history_days');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'max_image_uploads_per_month',
                'max_voice_minutes_per_month',
                'max_team_users',
                'has_api_access',
                'has_analytics_dashboard',
                'has_advanced_analytics',
                'has_branding_removal',
                'has_lead_capture',
                'has_custom_ai_guidance',
                'has_advanced_rules',
                'chat_history_days',
                'support_level',
            ]);
        });
    }
};
