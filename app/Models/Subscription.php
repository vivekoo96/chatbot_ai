<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'payment_gateway',
        'gateway_subscription_id',
        'gateway_customer_id',
        'razorpay_subscription_id',
        'razorpay_plan_id',
        'razorpay_customer_id',
        'status',
        'current_period_start',
        'current_period_end',
        'next_billing_at',
        'failed_payment_count',
        'grace_period_ends_at',
        'auto_renew',
        'cancel_at_period_end',
        'cancelled_at',
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'next_billing_at' => 'datetime',
        'grace_period_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'auto_renew' => 'boolean',
        'cancel_at_period_end' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' &&
            $this->current_period_end &&
            $this->current_period_end->isFuture();
    }

    /**
     * Check if subscription has expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' ||
            ($this->current_period_end && $this->current_period_end->isPast());
    }

    /**
     * Check if subscription is in grace period
     */
    public function isInGracePeriod(): bool
    {
        return $this->grace_period_ends_at &&
            $this->grace_period_ends_at->isFuture() &&
            $this->failed_payment_count >= config('subscription.max_failed_payments', 3);
    }

    /**
     * Increment failed payment count
     */
    public function incrementFailedPayments(): void
    {
        $this->increment('failed_payment_count');

        // Start grace period after max failures
        if ($this->failed_payment_count >= config('subscription.max_failed_payments', 3)) {
            $this->update([
                'grace_period_ends_at' => now()->addDays(config('subscription.grace_period_days', 5))
            ]);
        }
    }

    /**
     * Reset failed payment count
     */
    public function resetFailedPayments(): void
    {
        $this->update([
            'failed_payment_count' => 0,
            'grace_period_ends_at' => null,
        ]);
    }

    /**
     * Check if should downgrade to free plan
     */
    public function shouldDowngrade(): bool
    {
        return $this->grace_period_ends_at &&
            $this->grace_period_ends_at->isPast() &&
            $this->failed_payment_count >= config('subscription.max_failed_payments', 3);
    }

    /**
     * Mark subscription to cancel at period end
     */
    public function cancelAtPeriodEnd(): void
    {
        $this->update([
            'cancel_at_period_end' => true,
            'auto_renew' => false,
        ]);
    }

    /**
     * Mark subscription as cancelled
     */
    public function cancel(): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'auto_renew' => false,
        ]);
    }

    /**
     * Resume a cancelled subscription
     */
    public function resume(): void
    {
        $this->update([
            'status' => 'active',
            'cancelled_at' => null,
            'cancel_at_period_end' => false,
            'auto_renew' => true,
        ]);
    }
}
