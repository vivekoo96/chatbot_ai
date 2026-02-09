<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\User;
use App\Models\UserAddon;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Exception;

class AddonService
{
    protected $api;

    public function __construct()
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        $this->api = new Api($key, $secret);
    }

    /**
     * Purchase an add-on (create Razorpay order)
     */
    public function purchaseAddon(User $user, Addon $addon, string $currency = 'INR'): array
    {
        try {
            $amount = $addon->getTotalPriceWithGst($currency);

            $orderData = [
                'receipt' => 'addon_' . $addon->id . '_' . time(),
                'amount' => (int) round($amount * 100), // Amount in paisa/cents
                'currency' => $currency,
                'notes' => [
                    'addon_id' => $addon->id,
                    'user_id' => $user->id,
                    'type' => 'addon_purchase',
                ]
            ];

            $razorpayOrder = $this->api->order->create($orderData);

            Log::info('Add-on purchase order created', [
                'user_id' => $user->id,
                'addon_id' => $addon->id,
                'order_id' => $razorpayOrder['id']
            ]);

            return [
                'success' => true,
                'order_id' => $razorpayOrder['id'],
                'amount' => $amount,
                'currency' => $currency,
            ];

        } catch (Exception $e) {
            Log::error('Add-on purchase failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'addon_id' => $addon->id
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Activate add-on after successful payment
     */
    public function activateAddon(User $user, Addon $addon, string $paymentId, string $orderId, float $amountPaid): UserAddon
    {
        // Calculate expiry date (end of current billing cycle)
        $expiresAt = $user->billing_cycle_start
            ? \Carbon\Carbon::parse($user->billing_cycle_start)->addMonth()
            : now()->addMonth();

        $userAddon = UserAddon::create([
            'user_id' => $user->id,
            'addon_id' => $addon->id,
            'quantity_total' => $addon->quantity,
            'quantity_used' => 0,
            'quantity_remaining' => $addon->quantity,
            'razorpay_payment_id' => $paymentId,
            'razorpay_order_id' => $orderId,
            'amount_paid' => $amountPaid,
            'status' => 'active',
            'purchased_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        Log::info('Add-on activated', [
            'user_id' => $user->id,
            'addon_id' => $addon->id,
            'user_addon_id' => $userAddon->id,
            'expires_at' => $expiresAt
        ]);

        return $userAddon;
    }

    /**
     * Consume add-on quantity
     * Returns true if consumed successfully
     */
    public function consumeAddon(User $user, string $type, int $amount): bool
    {
        // Get active add-ons of this type (oldest first)
        $addons = UserAddon::where('user_id', $user->id)
            ->byType($type)
            ->active()
            ->orderBy('purchased_at', 'asc')
            ->get();

        $remaining = $amount;

        foreach ($addons as $userAddon) {
            if ($remaining <= 0)
                break;

            $toConsume = min($remaining, $userAddon->quantity_remaining);

            if ($userAddon->consume($toConsume)) {
                $remaining -= $toConsume;

                Log::info('Add-on consumed', [
                    'user_id' => $user->id,
                    'user_addon_id' => $userAddon->id,
                    'amount' => $toConsume,
                    'remaining' => $userAddon->quantity_remaining
                ]);
            }
        }

        return $remaining === 0;
    }

    /**
     * Get total available quantity from active add-ons
     */
    public function getAvailableQuantity(User $user, string $type): int
    {
        return UserAddon::where('user_id', $user->id)
            ->byType($type)
            ->active()
            ->sum('quantity_remaining');
    }

    /**
     * Expire add-ons past their expiry date
     */
    public function expireAddons(): int
    {
        $expired = UserAddon::where('status', 'active')
            ->where('expires_at', '<=', now())
            ->get();

        $count = 0;
        /** @var UserAddon $userAddon */
        foreach ($expired as $userAddon) {
            $userAddon->markAsExpired();
            $count++;

            Log::info('Add-on expired', [
                'user_id' => $userAddon->user_id,
                'user_addon_id' => $userAddon->id,
                'unused_quantity' => $userAddon->quantity_remaining
            ]);
        }

        return $count;
    }

    /**
     * Get user's active add-ons by type
     */
    public function getActiveAddons(User $user, string $type = null)
    {
        $query = UserAddon::where('user_id', $user->id)
            ->with('addon')
            ->active();

        if ($type) {
            $query->byType($type);
        }

        return $query->orderBy('expires_at', 'asc')->get();
    }
}
