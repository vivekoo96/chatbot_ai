<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Cashier\Billable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            if (!$user->plan_id) {
                $freePlan = Plan::where('slug', 'free')->first();
                if ($freePlan) {
                    $user->update(['plan_id' => $freePlan->id]);
                }
            }
        });

        static::deleting(function ($user) {
            $subscription = $user->subscriptions()->whereIn('status', ['active', 'paused', 'pending'])->first();
            if ($subscription) {
                try {
                    app(\App\Services\SubscriptionService::class)->cancelSubscription($subscription, true);
                } catch (\Exception $e) {
                    \Log::error('Failed to cancel subscription on user deletion', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_super_admin',
        'business_name',
        'industry',
        'country',
        'business_size',
        'plan_id',
        'messages_this_month',
        'billing_cycle_start',
        'currency',
        'stripe_customer_id',
        'razorpay_customer_id',
        'verification_otp',
        'verification_otp_expires_at',
        'invited_by_user_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_super_admin' => 'boolean',
            'verification_otp_expires_at' => 'datetime',
            'invited_by_user_id' => 'integer',
        ];
    }

    public function chatbots()
    {
        return $this->hasMany(\App\Models\Chatbot::class, 'user_id', 'id');
    }

    /**
     * Get the account owner (either this user or the person who invited them)
     */
    public function getOwner(): User
    {
        if ($this->invited_by_user_id) {
            return self::find($this->invited_by_user_id) ?? $this;
        }
        return $this;
    }
    /**
     * Get the user's role in the current team context
     */
    public function getTeamRole(): string
    {
        if (!$this->invited_by_user_id) {
            return 'owner';
        }

        $membership = TeamMember::where('user_id', $this->invited_by_user_id)
            ->where('email', $this->email)
            ->first();

        return $membership->role ?? 'viewer';
    }

    public function isTeamAdmin(): bool
    {
        $role = $this->getTeamRole();
        return $role === 'owner' || $role === 'admin';
    }

    public function isTeamViewer(): bool
    {
        return $this->getTeamRole() === 'viewer';
    }

    /**
     * Get the chatbots for the current context (either own or owner's)
     */
    public function getAvailableChatbots()
    {
        return $this->getOwner()->chatbots();
    }

    public function plan()
    {
        return $this->belongsTo(\App\Models\Plan::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(\App\Models\Subscription::class)->where('status', 'active');
    }

    public function hasReachedChatbotLimit(): bool
    {
        $owner = $this->getOwner();
        if (!$owner->plan) {
            return true; // No plan = can't create
        }
        $chatbotCount = $owner->chatbots()->count();
        return !$owner->plan->canCreateChatbot($chatbotCount);
    }

    public function hasReachedMessageLimit(): bool
    {
        $owner = $this->getOwner();
        if (!$owner->plan)
            return true;
        return !$owner->plan->canSendMessage($owner->messages_this_month);
    }

    public function hasReachedTeamLimit(): bool
    {
        $owner = $this->getOwner();
        if (!$owner->plan)
            return true;
        $currentCount = \App\Models\TeamMember::getCurrentTeamCount($owner->id);
        return !$owner->plan->canAddTeamMember($currentCount);
    }

    public function activities()
    {
        return $this->hasMany(\App\Models\Activity::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(\App\Models\TeamMember::class);
    }

    public function incrementMessageCount(): void
    {
        $this->getOwner()->increment('messages_this_month');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail);
    }
}
