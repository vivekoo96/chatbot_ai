<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVoiceUsage extends Model
{
    protected $fillable = [
        'user_id',
        'chatbot_id',
        'duration_seconds',
        'session_id',
        'month',
        'year',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    /**
     * Get current month's voice minutes for a user
     */
    public static function getCurrentMonthMinutes(int $userId): int
    {
        $now = now();
        $totalSeconds = self::where('user_id', $userId)
            ->where('year', $now->year)
            ->where('month', $now->month)
            ->sum('duration_seconds');

        return (int) ceil($totalSeconds / 60); // Convert to minutes
    }

    /**
     * Check if user can use voice features
     */
    public static function canUseVoice(User $user): bool
    {
        $currentMinutes = self::getCurrentMonthMinutes($user->id);
        return $user->plan->canUseVoice($currentMinutes);
    }
}
