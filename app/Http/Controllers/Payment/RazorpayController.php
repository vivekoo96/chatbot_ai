<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaymentSuccessful;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Exception;

class RazorpayController extends Controller
{
    protected $api;
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        Log::info('Razorpay Init', ['key_set' => !empty($key), 'secret_set' => !empty($secret)]);

        $this->api = new Api($key, $secret);
        $this->subscriptionService = $subscriptionService;
    }

    public function checkout(Request $request, Plan $plan)
    {
        try {
            $user = Auth::user();

            if (!$user->isTeamAdmin()) {
                abort(403, 'Only team administrators can change plans or start subscriptions.');
            }

            // Handle Free Plan or Super Admin Bypass
            if ($plan->price_inr <= 0 || $user->is_super_admin) {
                $user->update([
                    'plan_id' => $plan->id,
                    'messages_this_month' => 0,
                    'billing_cycle_start' => now()
                ]);

                Subscription::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'plan_id' => $plan->id,
                        'payment_gateway' => 'none',
                        'status' => 'active',
                        'current_period_start' => now(),
                        'current_period_end' => now()->addYears(99),
                        'auto_renew' => false,
                    ]
                );

                return redirect()->route('dashboard')->with('success', 'Plan activated successfully!');
            }

            // Check if user already has an active subscription to THIS plan
            $existingSub = $user->activeSubscription;
            if ($existingSub && $existingSub->plan_id === $plan->id && $existingSub->payment_gateway === 'razorpay') {
                return redirect()->route('dashboard')->with('success', 'You are already subscribed to this plan!');
            }

            // Create Razorpay Subscription
            Log::info('Starting checkout', ['user_id' => $user->id, 'plan_id' => $plan->id]);

            $result = $this->subscriptionService->createSubscription($user, $plan);

            if (!$result['success']) {
                Log::error('Subscription creation failed', ['error' => $result['error']]);
                return back()->with('error', 'Error creating subscription: ' . $result['error']);
            }

            Log::info('Subscription created successfully', [
                'subscription_id' => $result['subscription']->id,
                'has_short_url' => isset($result['short_url'])
            ]);

            /* 
            // Redirect to Razorpay hosted page or show subscription details
            if (isset($result['short_url']) && !empty($result['short_url'])) {
                Log::info('Redirecting to Razorpay short URL', ['short_url' => $result['short_url']]);
                return redirect($result['short_url']);
            }
            */

            Log::info('Returning checkout view', [
                'subscription_id' => $result['subscription']->id ?? null,
                'razorpay_subscription_id' => $result['razorpay_subscription']->id ?? null
            ]);

            return view('payment.razorpay-subscription', [
                'subscription' => $result['subscription'],
                'razorpay_subscription' => $result['razorpay_subscription'],
                'plan' => $plan,
                'user' => $user,
                'key' => config('services.razorpay.key')
            ]);

        } catch (Exception $e) {
            Log::error('Checkout exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request)
    {
        $user = Auth::user();

        if (!$user->isTeamAdmin()) {
            abort(403, 'Only team administrators can cancel subscriptions.');
        }

        $owner = $user->getOwner();
        $subscription = Subscription::where('user_id', $owner->id)->first();

        if (!$subscription) {
            return back()->with('error', 'No active subscription found');
        }

        $immediately = $request->boolean('immediately', false);
        $success = $this->subscriptionService->cancelSubscription($subscription, $immediately);

        if ($success) {
            $message = $immediately
                ? 'Subscription cancelled immediately'
                : 'Subscription will be cancelled at the end of your billing period';

            return back()->with('success', $message);
        }

        return back()->with('error', 'Failed to cancel subscription');
    }

    /**
     * Resume subscription
     */
    public function resume(Request $request)
    {
        $user = Auth::user();

        if (!$user->isTeamAdmin()) {
            abort(403, 'Only team administrators can resume subscriptions.');
        }

        $owner = $user->getOwner();
        $subscription = Subscription::where('user_id', $owner->id)->first();

        if (!$subscription) {
            return back()->with('error', 'No subscription found');
        }

        $success = $this->subscriptionService->resumeSubscription($subscription);

        if ($success) {
            return back()->with('success', 'Subscription resumed successfully');
        }

        return back()->with('error', 'Failed to resume subscription');
    }

    /**
     * Verify subscription payment from client-side
     */
    public function verify(Request $request)
    {
        try {
            $request->validate([
                'razorpay_payment_id' => 'required',
                'razorpay_subscription_id' => 'required',
                'razorpay_signature' => 'required',
            ]);

            $attributes = [
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_subscription_id' => $request->razorpay_subscription_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            // Verify signature
            $this->api->utility->verifyPaymentSignature($attributes);

            // Fetch local subscription
            $subscription = Subscription::where('razorpay_subscription_id', $request->razorpay_subscription_id)->first();

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Subscription record not found.'
                ], 404);
            }

            // Update subscription and user plan
            $user = Auth::user();
            $owner = $user->getOwner();

            // Fetch subscription details from Razorpay to get current period
            $rzpSubscription = $this->api->subscription->fetch($request->razorpay_subscription_id);

            $subscription->update([
                'status' => 'active',
                'current_period_start' => isset($rzpSubscription->current_start) ? now()->setTimestamp($rzpSubscription->current_start) : now(),
                'current_period_end' => isset($rzpSubscription->current_end) ? now()->setTimestamp($rzpSubscription->current_end) : now()->addMonth(),
            ]);

            $owner->update([
                'plan_id' => $subscription->plan_id,
                'billing_cycle_start' => now(),
                'messages_this_month' => 0,
            ]);

            Log::info('Subscription manually verified and activated', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'razorpay_subscription_id' => $request->razorpay_subscription_id
            ]);

            // Send invoice email immediately
            $amount = (float) ($subscription->plan->price_inr ?? 0);
            $user->notify(new PaymentSuccessful($subscription, $amount));

            return response()->json([
                'success' => true,
                'message' => 'Subscription activated successfully!'
            ]);

        } catch (SignatureVerificationError $e) {
            Log::error('Razorpay signature verification failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment signature.'
            ], 422);
        } catch (Exception $e) {
            Log::error('Razorpay verification error', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while verifying the subscription.'
            ], 500);
        }
    }
}
