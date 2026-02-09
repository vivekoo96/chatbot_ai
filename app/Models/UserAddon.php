<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddon extends Model
{
    protected $fillable = [
        'user_id',
        'addon_id',
        'quantity_total',
        'quantity_used',
        'quantity_remaining',
        'razorpay_payment_id',
        'razorpay_order_id',
        'amount_paid',
        'status',
        'purchased_at',
        'expires_at',
    ];

    protected $casts = [
        'quantity_total' => 'integer',
        'quantity_used' => 'integer',
        'quantity_remaining' => 'integer',
        'amount_paid' => 'decimal:2',
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }

    /**
     * Check if add-on is active and not expired
     */
    public function isActive(): bool
    {
        return $this->status === 'active' &&
            $this->quantity_remaining > 0 &&
            ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Consume quantity from add-on
     */
    public function consume(int $amount): bool
    {
        if (!$this->isActive() || $amount > $this->quantity_remaining) {
            return false;
        }

        $this->increment('quantity_used', $amount);
        $this->decrement('quantity_remaining', $amount);

        // Mark as consumed if fully used
        if ($this->quantity_remaining <= 0) {
            $this->update(['status' => 'consumed']);
        }

        return true;
    }

    /**
     * Get usage percentage
     */
    public function getUsagePercentage(): int
    {
        if ($this->quantity_total === 0) {
            return 0;
        }
        return (int) (($this->quantity_used / $this->quantity_total) * 100);
    }

    /**
     * Get remaining percentage
     */
    public function getRemainingPercentage(): int
    {
        return 100 - $this->getUsagePercentage();
    }

    /**
     * Mark as expired
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Check if expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' ||
            ($this->expires_at && $this->expires_at->isPast());
    }

    /**
     * Scope: Active add-ons
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('quantity_remaining', '>', 0)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope: By type
     */
    public function scopeByType($query, string $type)
    {
        return $query->whereHas('addon', function ($q) use ($type) {
            $q->where('type', $type);
        });
    }
}
