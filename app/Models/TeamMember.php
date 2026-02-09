<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'name',
        'role',
        'invitation_token',
        'invitation_sent_at',
        'invitation_accepted_at',
        'is_active',
    ];

    protected $casts = [
        'invitation_sent_at' => 'datetime',
        'invitation_accepted_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get current team member count for a user
     */
    public static function getCurrentTeamCount(int $userId): int
    {
        return self::where('user_id', $userId)
            ->where('is_active', true)
            ->count();
    }

    /**
     * Check if user can add more team members
     */
    public static function canAddMember(User $user): bool
    {
        $currentCount = self::getCurrentTeamCount($user->id);
        return $p = $user->plan ? $user->plan->canAddTeamMember($currentCount) : false;
    }

    /**
     * Check if the user's plan allows role-based access for team members.
     */
    public static function canAssignRoles(User $user): bool
    {
        return $user->plan?->has_role_based_access ?? false;
    }

    /**
     * Generate invitation token
     */
    public static function generateInvitationToken(): string
    {
        return Str::random(64);
    }

    /**
     * Check if invitation is still valid (e.g., 7 days)
     */
    public function isInvitationValid(): bool
    {
        if (!$this->invitation_sent_at || $this->invitation_accepted_at) {
            return false;
        }

        return $this->invitation_sent_at->addDays(7)->isFuture();
    }
}
