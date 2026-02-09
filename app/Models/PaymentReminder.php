<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentReminder extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'type',
        'sent_at',
        'is_sent',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Mark reminder as sent
     */
    public function markAsSent(): void
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);
    }

    /**
     * Check if reminder was already sent
     */
    public static function wasAlreadySent(int $userId, int $subscriptionId, string $type): bool
    {
        return self::where('user_id', $userId)
            ->where('subscription_id', $subscriptionId)
            ->where('type', $type)
            ->where('is_sent', true)
            ->exists();
    }
}
