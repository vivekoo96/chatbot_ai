<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'svg_icon',
        'price_inr',
        'price_usd',
        'max_chatbots',
        'max_messages_per_month',
        'max_image_uploads_per_month',
        'max_voice_minutes_per_month',
        'max_team_users',
        'has_api_access',
        'has_analytics_dashboard',
        'has_advanced_analytics',
        'has_branding_removal',
        'has_lead_capture',
        'has_custom_ai_guidance',
        'has_advanced_rules',
        'chat_history_days',
        'support_level',
        'features',
        'stripe_price_id',
        'razorpay_plan_id',
        'has_role_based_access',
        'has_team_access',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'has_api_access' => 'boolean',
        'has_analytics_dashboard' => 'boolean',
        'has_advanced_analytics' => 'boolean',
        'has_branding_removal' => 'boolean',
        'has_lead_capture' => 'boolean',
        'has_custom_ai_guidance' => 'boolean',
        'has_advanced_rules' => 'boolean',
        'has_role_based_access' => 'boolean',
        'has_team_access' => 'boolean',
        'price_inr' => 'decimal:2',
        'price_usd' => 'decimal:2',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Check if a user can create more chatbots
     */
    public function canCreateChatbot(int $currentCount): bool
    {
        if ($this->max_chatbots === -1) {
            return true; // Unlimited
        }
        return $currentCount < $this->max_chatbots;
    }

    /**
     * Check if a user can send more messages
     */
    public function canSendMessage(int $messagesSent): bool
    {
        if ($this->max_messages_per_month === -1) {
            return true; // Unlimited
        }
        return $messagesSent < $this->max_messages_per_month;
    }

    /**
     * Get price for specific currency
     */
    public function getPrice(string $currency = 'USD'): float
    {
        return (float) ($currency === 'INR' ? $this->price_inr : $this->price_usd);
    }

    /**
     * Get formatted price with currency symbol
     */
    public function getFormattedPrice(string $currency = 'USD'): string
    {
        $price = $this->getPrice($currency);
        $symbol = $currency === 'INR' ? '₹' : '$';
        return $symbol . number_format($price, 2);
    }

    public function getGstAmount(string $currency = 'INR'): float
    {
        $price = $this->getPrice($currency);
        $gstPercent = (float) Setting::getValue('gst_percent', 0);
        return ($price * $gstPercent) / 100;
    }

    public function getTotalPriceWithGst(string $currency = 'INR'): float
    {
        return $this->getPrice($currency) + $this->getGstAmount($currency);
    }

    /**
     * Check if a user can upload more images
     */
    public function canUploadImage(int $currentUploads): bool
    {
        if ($this->max_image_uploads_per_month === -1) {
            return true; // Unlimited
        }
        return $currentUploads < $this->max_image_uploads_per_month;
    }

    /**
     * Check if a user can use voice features
     */
    public function canUseVoice(int $minutesUsed): bool
    {
        if ($this->max_voice_minutes_per_month === -1) {
            return true; // Unlimited
        }
        return $minutesUsed < $this->max_voice_minutes_per_month;
    }

    /**
     * Check if a user can add more team members
     */
    public function canAddTeamMember(int $currentMembers): bool
    {
        if ($this->max_team_users === -1) {
            return true; // Unlimited
        }
        return $currentMembers < $this->max_team_users;
    }

    /**
     * Check if plan has specific feature
     */
    public function hasFeature(string $feature): bool
    {
        $featureMap = [
            'api_access' => $this->has_api_access,
            'analytics_dashboard' => $this->has_analytics_dashboard,
            'advanced_analytics' => $this->has_advanced_analytics,
            'branding_removal' => $this->has_branding_removal,
            'lead_capture' => $this->has_lead_capture,
            'custom_ai_guidance' => $this->has_custom_ai_guidance,
            'advanced_rules' => $this->has_advanced_rules,
        ];

        return $featureMap[$feature] ?? false;
    }
}
