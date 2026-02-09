<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddon;
use App\Models\Subscription;
use Illuminate\Http\Request;

class UserBillingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::with(['plan', 'activeSubscription', 'chatbots'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.user-billing', compact('users', 'search'));
    }

    public function show(User $user)
    {
        $user->load(['plan', 'activeSubscription', 'chatbots']);

        // Get all subscriptions
        $subscriptions = Subscription::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all add-ons
        $activeAddons = UserAddon::where('user_id', $user->id)
            ->with('addon')
            ->active()
            ->orderBy('expires_at', 'asc')
            ->get();

        $expiredAddons = UserAddon::where('user_id', $user->id)
            ->with('addon')
            ->whereIn('status', ['expired', 'consumed'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Usage stats
        $messagesUsed = $user->messages_this_month ?? 0;

        // Base plan limit + active message add-ons
        $baseLimit = $user->plan->max_messages_per_month ?? 0;
        $addonLimit = UserAddon::where('user_id', $user->id)
            ->active()
            ->whereHas('addon', function ($q) {
                $q->where('type', 'messages');
            })
            ->sum('quantity_remaining');

        $messagesLimit = $baseLimit === -1 ? -1 : ($baseLimit + $addonLimit);

        $totalChatbots = $user->chatbots->count();
        $activeChatbots = $user->chatbots->where('is_active', true)->count();
        $chatbotsLimit = $user->plan->max_chatbots ?? 0;

        return view('admin.user-billing-detail', compact(
            'user',
            'subscriptions',
            'activeAddons',
            'expiredAddons',
            'messagesUsed',
            'messagesLimit',
            'activeChatbots',
            'totalChatbots',
            'chatbotsLimit'
        ));
    }
}
