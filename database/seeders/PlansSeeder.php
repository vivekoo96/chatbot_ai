<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlansSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'razorpay_plan_id' => null, // Free plan, no Razorpay needed
                'price_inr' => 0,
                'price_usd' => 0,
                'max_chatbots' => 1,
                'max_messages_per_month' => 150,
                'max_image_uploads_per_month' => 0,
                'max_voice_minutes_per_month' => 0,
                'max_team_users' => 1,
                'has_api_access' => false,
                'has_analytics_dashboard' => false,
                'has_advanced_analytics' => false,
                'has_branding_removal' => false,
                'has_lead_capture' => false,
                'has_custom_ai_guidance' => false,
                'has_advanced_rules' => false,
                'has_team_access' => false,
                'chat_history_days' => 7,
                'support_level' => 'email',
                'features' => [
                    '1 Chatbot',
                    '150 Text Messages / month',
                    'Text Chat Only',
                    'Website Widget',
                    'Hemnix Assist Branding',
                    'Email Support',
                ],
                'stripe_price_id' => 'price_free',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'razorpay_plan_id' => 'plan_SARXQ7z2U440yy', // ₹499 plan
                'price_inr' => 499,
                'price_usd' => 6.99,
                'max_chatbots' => 2,
                'max_messages_per_month' => 2000,
                'max_image_uploads_per_month' => 30,
                'max_voice_minutes_per_month' => 0,
                'max_team_users' => 1,
                'has_api_access' => false,
                'has_analytics_dashboard' => false,
                'has_advanced_analytics' => false,
                'has_branding_removal' => true,
                'has_lead_capture' => true,
                'has_custom_ai_guidance' => true,
                'has_advanced_rules' => false,
                'has_team_access' => false,
                'chat_history_days' => 30,
                'support_level' => 'email',
                'features' => [
                    'Up to 2 Chatbots',
                    '2,000 Text Messages / month',
                    '30 Image Uploads / month',
                    'Custom AI Guidance',
                    'Lead Capture',
                    'Chat History (30 days)',
                    'Remove Branding',
                    'Email Support',
                ],
                'stripe_price_id' => 'price_starter',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'razorpay_plan_id' => 'plan_SARXRIn3OmmPAm', // ₹1,499 plan
                'price_inr' => 1499,
                'price_usd' => 19.99,
                'max_chatbots' => 5,
                'max_messages_per_month' => 8000,
                'max_image_uploads_per_month' => 200,
                'max_voice_minutes_per_month' => 20,
                'max_team_users' => 3,
                'has_api_access' => false,
                'has_analytics_dashboard' => true,
                'has_advanced_analytics' => false,
                'has_branding_removal' => true,
                'has_lead_capture' => true,
                'has_custom_ai_guidance' => true,
                'has_advanced_rules' => true,
                'has_team_access' => true,
                'has_role_based_access' => false,
                'chat_history_days' => 90,
                'support_level' => 'priority',
                'features' => [
                    'Up to 5 Chatbots',
                    '8,000 Text Messages / month',
                    '200 Image Uploads / month',
                    '20 Voice Minutes / month',
                    'Advanced Rules & Guidance',
                    'Analytics Dashboard',
                    'Team Access (up to 3 users)',
                    'Priority Support',
                ],
                'stripe_price_id' => 'price_pro',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'razorpay_plan_id' => 'plan_SARXSMk5aswYdi', // ₹2,499 plan
                'price_inr' => 2499,
                'price_usd' => 34.99,
                'max_chatbots' => 10,
                'max_messages_per_month' => 15000,
                'max_image_uploads_per_month' => 400,
                'max_voice_minutes_per_month' => 50,
                'max_team_users' => 6,
                'has_api_access' => false,
                'has_analytics_dashboard' => true,
                'has_advanced_analytics' => true,
                'has_branding_removal' => true,
                'has_lead_capture' => true,
                'has_custom_ai_guidance' => true,
                'has_advanced_rules' => true,
                'has_team_access' => true,
                'has_role_based_access' => false,
                'chat_history_days' => 180,
                'support_level' => 'priority',
                'features' => [
                    'Up to 10 Chatbots',
                    '15,000 Text Messages / month',
                    '400 Image Uploads / month',
                    '50 Voice Minutes / month',
                    'Advanced Analytics',
                    'Advanced Rules',
                    'Team Access (up to 6 users)',
                    'Priority Email & Chat Support',
                ],
                'stripe_price_id' => 'price_enterprise',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'razorpay_plan_id' => 'plan_SARXTQTF8FFvzV', // ₹4,999 plan
                'price_inr' => 4999,
                'price_usd' => 64.99,
                'max_chatbots' => 20,
                'max_messages_per_month' => 25000,
                'max_image_uploads_per_month' => 700,
                'max_voice_minutes_per_month' => 90,
                'max_team_users' => 10,
                'has_api_access' => true,
                'has_analytics_dashboard' => true,
                'has_advanced_analytics' => true,
                'has_branding_removal' => true,
                'has_lead_capture' => true,
                'has_custom_ai_guidance' => true,
                'has_advanced_rules' => true,
                'has_team_access' => true,
                'has_role_based_access' => true,
                'chat_history_days' => 365,
                'support_level' => 'dedicated',
                'features' => [
                    'Up to 20 Chatbots',
                    '25,000 Text Messages / month',
                    '700 Image Uploads / month',
                    '90 Voice Minutes / month',
                    'Advanced Analytics',
                    'Role-Based Access',
                    'API Access',
                    'Dedicated Onboarding & SLA Support',
                ],
                'stripe_price_id' => 'price_business',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('plans');

        foreach ($plans as $plan) {
            // Filter out keys that don't exist in the database to avoid errors
            $filteredPlan = array_intersect_key($plan, array_flip($columns));

            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                $filteredPlan
            );
        }

        $this->command->info('✅ Created 4 subscription plans: Free, Starter, Pro, Business!');
    }
}
