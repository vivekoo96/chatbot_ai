<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class PlanUsage extends Component
{
    public function render()
    {
        $user = Auth::user();
        $owner = $user->getOwner();

        // Force fresh query from database
        $owner->refresh();
        $owner->load('plan');

        // Auto-sync plan from active subscription if mismatch
        $activeSubscription = \App\Models\Subscription::where('user_id', $owner->id)
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($activeSubscription && $activeSubscription->plan_id != $owner->plan_id) {
            \Log::info('PlanUsage: Auto-syncing plan from subscription', [
                'user_id' => $owner->id,
                'old_plan_id' => $owner->plan_id,
                'new_plan_id' => $activeSubscription->plan_id,
                'subscription_id' => $activeSubscription->id
            ]);

            $owner->update([
                'plan_id' => $activeSubscription->plan_id,
                'billing_cycle_start' => $activeSubscription->current_period_start,
            ]);

            $owner->refresh();
            $owner->load('plan');
        }

        // Fetch current subscription (including cancelled/paused)
        $latestSubscription = \App\Models\Subscription::where('user_id', $owner->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // If owner doesn't have a plan, assign the free plan
        if (!$owner->plan) {
            $freePlan = \App\Models\Plan::where('slug', 'free')->first();
            if ($freePlan) {
                $owner->plan_id = $freePlan->id;
                $owner->save();
                $owner->load('plan');
            }
        }

        // Get usage statistics
        $totalChatbots = $owner->chatbots()->count();
        $activeChatbots = $owner->chatbots()->where('is_active', true)->count();
        $chatbotLimit = $owner->plan?->max_chatbots ?? 1;
        $chatbotPercentage = $chatbotLimit > 0 ? min(($totalChatbots / $chatbotLimit) * 100, 100) : 100;

        $messagesUsed = $owner->messages_this_month ?? 0;

        // Base plan limit + active message add-ons
        $baseLimit = $owner->plan?->max_messages_per_month ?? 50;
        $addonLimit = \App\Models\UserAddon::where('user_id', $owner->id)
            ->active()
            ->whereHas('addon', function ($q) {
                $q->where('type', 'messages');
            })
            ->sum('quantity_remaining');

        $messagesLimit = $baseLimit === -1 ? -1 : ($baseLimit + $addonLimit);
        $messagesPercentage = $messagesLimit > 0 ? min(($messagesUsed / $messagesLimit) * 100, 100) : 100;

        $teamCount = \App\Models\TeamMember::getCurrentTeamCount($owner->id);
        $teamLimit = $owner->plan?->max_team_users ?? 1;
        $teamPercentage = $teamLimit > 0 ? min(($teamCount / $teamLimit) * 100, 100) : 100;

        // Dynamically update features list to show correct limits
        $features = [];
        if ($owner->plan && isset($owner->plan->features) && is_array($owner->plan->features)) {
            $features = $owner->plan->features;
            foreach ($features as $key => $feature) {
                if (preg_match('/Messages\s*\/\s*month/i', $feature)) {
                    $features[$key] = number_format($messagesLimit) . ' Messages/month';
                }
                if (preg_match('/\d+\s*Chatbots/i', $feature) || preg_match('/Chatbots/i', $feature)) {
                    $features[$key] = number_format($chatbotLimit) . ' Chatbots';
                }
            }
        }

        return view('livewire.dashboard.plan-usage', [
            'plan' => $owner->plan,
            'owner' => $owner,
            'features' => $features,
            'currency' => $owner->currency ?? 'USD',
            'chatbot_count' => $totalChatbots,
            'active_chatbot_count' => $activeChatbots,
            'chatbot_limit' => $chatbotLimit,
            'chatbot_percentage' => $chatbotPercentage,
            'messages_used' => $messagesUsed,
            'messages_limit' => $messagesLimit,
            'messages_percentage' => $messagesPercentage,
            'team_count' => $teamCount,
            'team_limit' => $teamLimit,
            'team_percentage' => $teamPercentage,
            'subscription' => $latestSubscription,
            'subscription_status' => $latestSubscription?->status ?? 'none',
        ]);
    }
}

