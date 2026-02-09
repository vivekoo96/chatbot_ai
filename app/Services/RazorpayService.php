<?php

namespace App\Services;

use App\Models\Plan;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Exception;

class RazorpayService
{
    protected $api;

    public function __construct()
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');

        if ($key && $secret) {
            $this->api = new Api($key, $secret);
        }
    }

    /**
     * Create a plan in Razorpay
     *
     * @param Plan $plan
     * @return string|null The created Razorpay Plan ID
     */
    public function createPlan(Plan $plan)
    {
        if (!$this->api) {
            Log::error('Razorpay API not configured properly.');
            return null;
        }

        try {
            // Check if existing ID is valid
            if ($plan->razorpay_plan_id) {
                try {
                    $this->api->plan->fetch($plan->razorpay_plan_id);
                    Log::info('Razorpay plan ID is valid, skipping creation', ['razorpay_plan_id' => $plan->razorpay_plan_id]);
                    return $plan->razorpay_plan_id;
                } catch (Exception $e) {
                    Log::warning('Razorpay plan ID invalid or from different account. Creating new.', [
                        'invalid_id' => $plan->razorpay_plan_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $planData = [
                'period' => 'monthly',
                'interval' => 1,
                'item' => [
                    'name' => $plan->name,
                    'amount' => (int) ($plan->price_inr * 100), // Convert to paise
                    'currency' => 'INR',
                    'description' => $plan->name . " Plan - Unlimited features"
                ],
                'notes' => [
                    'plan_id' => $plan->id,
                    'local_slug' => $plan->slug
                ]
            ];

            $createdPlan = $this->api->plan->create($planData);

            Log::info('Razorpay plan created successfully', [
                'local_plan_id' => $plan->id,
                'razorpay_plan_id' => $createdPlan->id
            ]);

            return $createdPlan->id;
        } catch (Exception $e) {
            Log::error('Failed to create Razorpay plan', [
                'local_plan_id' => $plan->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Sync local plan with Razorpay (creates new if needed)
     */
    public function syncPlan(Plan $plan)
    {
        // Razorpay plans are immutable for amount/currency.
        // If price changed, we MUST create a new one.
        return $this->createPlan($plan);
    }
}
