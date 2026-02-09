<?php

namespace App\Console\Commands;

use App\Models\Plan;
use Illuminate\Console\Command;
use Razorpay\Api\Api;
use Exception;

class CreateRazorpayPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'razorpay:create-plans';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Create or update plans in Razorpay for all active local plans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');

        if (!$key || !$secret) {
            $this->error('❌ Razorpay credentials not configured!');
            return 1;
        }

        try {
            $api = new Api($key, $secret);
            $this->info('✅ Connected to Razorpay API');
        } catch (Exception $e) {
            $this->error('❌ Failed to connect to Razorpay: ' . $e->getMessage());
            return 1;
        }

        // Get all active plans from database
        $plans = Plan::where('is_active', true)
            ->where('price_inr', '>', 0) // Only paid plans
            ->get();

        if ($plans->isEmpty()) {
            $this->warn('⚠️  No active paid plans found in database');
            return 0;
        }

        $this->info("\n📋 Creating/Updating Razorpay Plans\n");
        $this->info('─────────────────────────────────────');

        $successCount = 0;
        $skipCount = 0;

        foreach ($plans as $plan) {
            try {
                // Skip free plans
                if ($plan->price_inr <= 0) {
                    $this->line("⏭️  Skipping {$plan->name} (Free Plan)");
                    $skipCount++;
                    continue;
                }

                // Check if plan already exists in Razorpay
                if ($plan->razorpay_plan_id) {
                    try {
                        $existingPlan = $api->plan->fetch($plan->razorpay_plan_id);
                        $this->line("✅ {$plan->name} - Already exists (ID: {$plan->razorpay_plan_id})");
                        $skipCount++;
                        continue;
                    } catch (Exception $e) {
                        // Plan doesn't exist in Razorpay, will create new one
                        $this->line("⚠️  {$plan->name} - Local ID invalid, creating new...");
                    }
                }

                // Create new plan in Razorpay
                $planData = [
                    'period' => 'monthly',
                    'interval' => 1,
                    'amount' => (int)($plan->price_inr * 100), // Convert to paise
                    'currency' => 'INR',
                    'description' => "{$plan->name} Plan - {$plan->max_chatbots} Chatbots, {$plan->max_messages_per_month} Messages/month",
                    'notes' => [
                        'plan_id' => $plan->id,
                        'plan_name' => $plan->name,
                        'max_chatbots' => $plan->max_chatbots,
                        'max_messages' => $plan->max_messages_per_month,
                    ]
                ];

                $createdPlan = $api->plan->create($planData);

                // Update local plan with Razorpay ID
                $plan->update(['razorpay_plan_id' => $createdPlan->id]);

                $this->line("✅ {$plan->name} - Created (ID: {$createdPlan->id})");
                $successCount++;

            } catch (Exception $e) {
                $this->error("❌ {$plan->name} - Error: " . $e->getMessage());
            }
        }

        // Summary
        $this->info("\n─────────────────────────────────────");
        $this->info("📊 Summary:");
        $this->line("   ✅ Created/Updated: $successCount");
        $this->line("   ⏭️  Skipped: $skipCount");
        $this->info("─────────────────────────────────────\n");

        $this->info("✅ Razorpay plans sync completed!");
        return 0;
    }
}
