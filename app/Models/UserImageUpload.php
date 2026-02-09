<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserImageUpload extends Model
{
    protected $fillable = [
        'user_id',
        'chatbot_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'month',
        'year',
    ];

    protected $casts = [
        'file_size' => 'integer',
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
     * Get current month's upload count for a user
     */
    public static function getCurrentMonthCount(int $userId): int
    {
        $now = now();
        return self::where('user_id', $userId)
            ->where('year', $now->year)
            ->where('month', $now->month)
            ->count();
    }

    /**
     * Check if user can upload more images
     */
    public static function canUpload(User $user): bool
    {
        $currentCount = self::getCurrentMonthCount($user->id);
        return $user->plan->canUploadImage($currentCount);
    }
}
