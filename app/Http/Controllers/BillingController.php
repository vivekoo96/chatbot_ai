<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserAddon;
use App\Models\Subscription;

class BillingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->isTeamAdmin()) {
            abort(403, 'Only team administrators can access billing information.');
        }

        $owner = $user->getOwner();
        $plan = $owner->plan;
        $subscription = $owner->activeSubscription;

        // Get usage stats
        $messagesUsed = $owner->messages_this_month ?? 0;
        $messagesLimit = $plan->max_messages_per_month;
        $messagesFromAddons = app(\App\Services\AddonService::class)->getAvailableQuantity($owner, 'messages');

        // Get active add-ons
        $activeAddons = UserAddon::where('user_id', $owner->id)
            ->with('addon')
            ->active()
            ->orderBy('expires_at', 'asc')
            ->get();

        // Get payment history (subscriptions + add-ons)
        $payments = collect();

        // Add subscription payments
        if ($subscription) {
            $payments->push([
                'id' => $subscription->id,
                'type' => 'subscription',
                'description' => $plan->name . ' Plan',
                'amount' => $subscription->plan->price_inr,
                'status' => $subscription->status,
                'date' => $subscription->created_at,
                'next_billing' => $subscription->next_billing_at,
            ]);
        }

        // Add add-on payments
        $addonPayments = UserAddon::where('user_id', $owner->id)
            ->with('addon')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($userAddon) {
                return [
                    'id' => $userAddon->id,
                    'type' => 'addon',
                    'description' => $userAddon->addon->name,
                    'amount' => $userAddon->amount_paid,
                    'status' => $userAddon->status,
                    'date' => $userAddon->purchased_at,
                    'expires_at' => $userAddon->expires_at,
                ];
            });

        $payments = $payments->merge($addonPayments)->sortByDesc('date');

        return view('dashboard.billing', compact(
            'user',
            'plan',
            'subscription',
            'messagesUsed',
            'messagesLimit',
            'messagesFromAddons',
            'activeAddons',
            'payments'
        ));
    }
}
