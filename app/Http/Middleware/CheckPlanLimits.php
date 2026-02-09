<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TeamMember;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    public function handle(Request $request, Closure $next, string $limitType = 'chatbot'): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admins bypass all limits
        if ($user->is_super_admin) {
            return $next($request);
        }

        $owner = $user->getOwner();

        // Assign free plan if owner has no plan
        if (!$owner->plan_id) {
            $freePlan = \App\Models\Plan::where('slug', 'free')->first();
            if ($freePlan) {
                $owner->update(['plan_id' => $freePlan->id]);
                $owner->load('plan');
            }
        }

        // Check limits based on type
        if ($limitType === 'chatbot' && $user->hasReachedChatbotLimit()) {
            return response()->json([
                'error' => 'You have reached your chatbot limit. Please upgrade your plan.',
                'current_plan' => $owner->plan->name,
                'max_chatbots' => $owner->plan->max_chatbots,
            ], 403);
        }

        if ($limitType === 'message' && $user->hasReachedMessageLimit()) {
            return response()->json([
                'error' => 'You have reached your monthly message limit. Please upgrade your plan.',
                'current_plan' => $owner->plan->name,
                'messages_used' => $owner->messages_this_month,
                'messages_limit' => $owner->plan->max_messages_per_month,
            ], 403);
        }

        if ($limitType === 'team' && $user->hasReachedTeamLimit()) {
            return response()->json([
                'error' => 'You have reached your team member limit. Please upgrade your plan.',
                'current_plan' => $owner->plan->name,
                'team_members_used' => TeamMember::getCurrentTeamCount($owner->id),
                'team_members_limit' => $owner->plan->max_team_users,
            ], 403);
        }

        return $next($request);
    }
}

