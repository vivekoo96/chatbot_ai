<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Setting;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Exception;

class SubscriptionService
{
    protected $api;

    public function __construct()
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        $this->api = new Api($key, $secret);
    }

    /**
     * Create a Razorpay subscription for a user
     */
    public function createSubscription(User $user, Plan $plan): array
    {
        try {
            // Validate that plan has razorpay_plan_id
            if (empty($plan->razorpay_plan_id)) {
                Log::error('Plan does not have Razorpay Plan ID', [
                    'plan_id' => $plan->id,
                    'plan_slug' => $plan->slug,
                    'user_id' => $user->id
                ]);
                return [
                    'success' => false,
                    'error' => 'This plan is not configured for online payments. Please contact support.',
                ];
            }

            // Create or get Razorpay customer
            $customerId = $this->getOrCreateCustomer($user);

            Log::info('Creating subscription with', [
                'plan_id' => $plan->razorpay_plan_id,
                'customer_id' => $customerId,
                'user_id' => $user->id
            ]);

            $subscriptionData = [
                'plan_id' => $plan->razorpay_plan_id,
                'customer_id' => $customerId,
                'total_count' => 120, // 10 years (Razorpay requires count >= 1)
                'quantity' => 1,
                'notes' => [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                ]
            ];

            // Add GST as an addon so it shows in the total payment modal
            $gstAmount = $plan->getGstAmount('INR');
            if ($gstAmount > 0) {
                $subscriptionData['addons'] = [
                    [
                        'item' => [
                            'name' => 'GST (' . Setting::getValue('gst_percent', 0) . '%)',
                            'amount' => (int) round($gstAmount * 100), // convert to paise
                            'currency' => 'INR'
                        ]
                    ]
                ];
            }

            // Check if user has an active Razorpay subscription
            $existingSub = Subscription::where('user_id', $user->id)
                ->where('payment_gateway', 'razorpay')
                ->where('status', 'active')
                ->first();

            if ($existingSub && $existingSub->razorpay_subscription_id) {
                try {
                    $razorpaySub = $this->api->subscription->fetch($existingSub->razorpay_subscription_id);

                    // If it's the same plan, just return it
                    if ($razorpaySub->plan_id === $plan->razorpay_plan_id) {
                        Log::info('User already has active subscription for this plan');
                        return [
                            'success' => true,
                            'subscription' => $existingSub,
                            'razorpay_subscription' => $razorpaySub,
                            'short_url' => $razorpaySub->short_url ?? null,
                        ];
                    }

                    // If it's a different plan, cancel the old one
                    Log::info('Cancelling old subscription for plan upgrade');
                    $razorpaySub->cancel(['cancel_at_cycle_end' => 0]); // Cancel immediately
                } catch (Exception $e) {
                    Log::warning('Failed to fetch/cancel old subscription', ['error' => $e->getMessage()]);
                }
            }

            Log::info('Sending request to Razorpay API');
            $razorpaySubscription = $this->api->subscription->create($subscriptionData);

            Log::info('Razorpay Subscription Created', [
                'subscription_id' => $razorpaySubscription->id,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'short_url' => $razorpaySubscription->short_url ?? null
            ]);

            // Create local subscription record
            $subscription = Subscription::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan_id' => $plan->id,
                    'payment_gateway' => 'razorpay',
                    'razorpay_subscription_id' => $razorpaySubscription->id,
                    'razorpay_plan_id' => $plan->razorpay_plan_id,
                    'razorpay_customer_id' => $customerId,
                    'status' => 'pending', // Will be activated via webhook
                    'auto_renew' => true,
                    'failed_payment_count' => 0,
                ]
            );

            return [
                'success' => true,
                'subscription' => $subscription,
                'razorpay_subscription' => $razorpaySubscription,
                'short_url' => $razorpaySubscription->short_url ?? null,
            ];

        } catch (Exception $e) {
            Log::error('Subscription Creation Failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get or create Razorpay customer
     */
    protected function getOrCreateCustomer(User $user): string
    {
        // 1. Check local DB
        if (!empty($user->razorpay_customer_id)) {
            return $user->razorpay_customer_id;
        }

        // 2. Check Razorpay by Email
        try {
            $customers = $this->api->customer->all(['email' => $user->email, 'count' => 1]);
            if ($customers->count() > 0) {
                $customerId = $customers->items[0]->id;
                $user->update(['razorpay_customer_id' => $customerId]);
                Log::info('Razorpay Customer Found via API', ['id' => $customerId, 'email' => $user->email]);
                return $customerId;
            }
        } catch (Exception $e) {
            Log::warning('Error searching customer in Razorpay', ['error' => $e->getMessage()]);
        }

        // 3. Create New Customer
        $customerData = [
            'name' => $user->name,
            'email' => $user->email,
            'contact' => $user->phone ?? '', // Ensure phone is handled if available
            'notes' => [
                'user_id' => $user->id,
                'source' => 'chatboat_app'
            ]
        ];

        try {
            $customer = $this->api->customer->create($customerData);
            $user->update(['razorpay_customer_id' => $customer->id]);

            Log::info('Razorpay Customer Created', ['id' => $customer->id, 'user_id' => $user->id]);

            return $customer->id;
        } catch (Exception $e) {
            Log::error('Razorpay Customer Creation Failed', ['error' => $e->getMessage()]);
            // Attempt to recover if email already exists but search failed earlier
            if (str_contains($e->getMessage(), 'already exists')) {
                $customers = $this->api->customer->all(['email' => $user->email, 'count' => 1]);
                if ($customers->count() > 0) {
                    $customerId = $customers->items[0]->id;
                    $user->update(['razorpay_customer_id' => $customerId]);
                    return $customerId;
                }
            }
            throw $e;
        }
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Subscription $subscription, bool $immediately = false): bool
    {
        try {
            if ($immediately) {
                // Cancel immediately in Razorpay
                $this->api->subscription->fetch($subscription->razorpay_subscription_id)->cancel();
                $subscription->cancel();
            } else {
                // Pause at period end in Razorpay (Reversible unlike cancel_at_cycle_end)
                // Note: In Razorpay, 'pause' at cycle end is the only way to support "Resume" later.
                $this->api->subscription->fetch($subscription->razorpay_subscription_id)->pause(['pause_at' => 'cycle_end']);
                $subscription->cancelAtPeriodEnd();
            }

            Log::info('Subscription Cancelled', [
                'subscription_id' => $subscription->id,
                'immediately' => $immediately
            ]);

            return true;

        } catch (Exception $e) {
            Log::error('Subscription Cancellation Failed', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id
            ]);

            return false;
        }
    }

    /**
     * Resume a cancelled subscription
     */
    public function resumeSubscription(Subscription $subscription): bool
    {
        try {
            // Fetch current state from Razorpay
            $rzpSubscription = $this->api->subscription->fetch($subscription->razorpay_subscription_id);

            // If it's active but scheduled to pause/cancel, resume() removes the scheduled action
            // If it's paused, resume() reactivates it
            try {
                $rzpSubscription->resume();
            } catch (Exception $e) {
                // If it's already active, it means we can't "undo" a 'cancel_at_cycle_end' via API, 
                // or it's already in the state we want. We'll ignore this specific error to allow local resume.
                if (str_contains(strtolower($e->getMessage()), 'active state')) {
                    Log::info('Razorpay subscription already active, ignoring resume error', [
                        'subscription_id' => $subscription->id,
                        'razorpay_id' => $subscription->razorpay_subscription_id
                    ]);
                } else {
                    throw $e;
                }
            }

            $subscription->resume();

            Log::info('Subscription Resumed', [
                'subscription_id' => $subscription->id
            ]);

            return true;

        } catch (Exception $e) {
            Log::error('Subscription Resume Failed', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id
            ]);

            return false;
        }
    }

    /**
     * Handle grace period expiry - downgrade to free plan
     */
    public function handleGracePeriodExpiry(Subscription $subscription): void
    {
        $freePlan = Plan::where('slug', 'free')->first();

        if (!$freePlan) {
            Log::error('Free plan not found for downgrade');
            return;
        }

        $user = $subscription->user;

        // Update subscription to free plan
        $subscription->update([
            'plan_id' => $freePlan->id,
            'status' => 'active',
            'failed_payment_count' => 0,
            'grace_period_ends_at' => null,
            'current_period_start' => now(),
            'current_period_end' => now()->addYears(99), // Permanent
        ]);

        // Update user's plan
        $user->update([
            'plan_id' => $freePlan->id,
            'billing_cycle_start' => now(),
            'messages_this_month' => 0,
        ]);

        Log::info('User downgraded to free plan after grace period', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id
        ]);
    }

    /**
     * Sync subscription status with Razorpay
     */
    public function syncWithRazorpay(Subscription $subscription): void
    {
        try {
            $razorpaySub = $this->api->subscription->fetch($subscription->razorpay_subscription_id);

            $subscription->update([
                'status' => $this->mapRazorpayStatus($razorpaySub->status),
                'current_period_start' => $razorpaySub->current_start ? now()->setTimestamp($razorpaySub->current_start) : null,
                'current_period_end' => $razorpaySub->current_end ? now()->setTimestamp($razorpaySub->current_end) : null,
                'next_billing_at' => $razorpaySub->charge_at ? now()->setTimestamp($razorpaySub->charge_at) : null,
            ]);

            Log::info('Subscription synced with Razorpay', [
                'subscription_id' => $subscription->id,
                'razorpay_status' => $razorpaySub->status
            ]);

        } catch (Exception $e) {
            Log::error('Subscription sync failed', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id
            ]);
        }
    }

    /**
     * Map Razorpay status to local status
     */
    protected function mapRazorpayStatus(string $razorpayStatus): string
    {
        return match ($razorpayStatus) {
            'created', 'authenticated', 'active' => 'active',
            'paused' => 'paused',
            'cancelled' => 'cancelled',
            'expired', 'completed' => 'expired',
            default => 'pending',
        };
    }
}
