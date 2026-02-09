<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class StripeController extends Controller
{
    public function checkout(Request $request, Plan $plan)
    {
        $user = $request->user();

        // 1. Validate if plan has a stripe price ID
        if (!$plan->stripe_price_id) {
            return back()->with('error', 'This plan is not available for international payments yet.');
        }

        // 2. Handle Free Plan or Super Admin Bypass
        if ($plan->price_usd == 0 || $user->is_super_admin) {
            $user->plan_id = $plan->id;
            $user->save();
            return redirect()->route('dashboard')->with('success', 'Plan subscribed successfully!' . ($user->is_super_admin && $plan->price_usd > 0 ? ' (Admin Bypass)' : ''));
        }

        // 3. Create Stripe Checkout Session
        try {
            return $user->newSubscription('default', $plan->stripe_price_id)
                ->checkout([
                    'success_url' => route('payment.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('payment.stripe.cancel'),
                ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        return redirect()->route('dashboard')->with('success', 'Thank you! Your subscription is being processed.');
    }

    public function cancel()
    {
        return redirect()->route('pricing')->with('error', 'Payment was cancelled.');
    }
}
