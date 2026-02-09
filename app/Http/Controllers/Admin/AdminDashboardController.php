<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Chatbot;
use App\Models\Conversation;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Basic Stats
        $stats = [
            'total_users' => User::count(),
            'total_chatbots' => Chatbot::count(),
            'total_conversations' => Conversation::count(),
            'total_messages' => \App\Models\Message::count(),
            'active_chatbots' => Chatbot::where('is_active', true)->count(),
            'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'today_conversations' => Conversation::whereDate('created_at', today())->count(),
            'avg_response_time' => '0.8s',
            'storage_usage' => '12 MB',
            'api_calls_today' => \App\Models\Message::whereDate('created_at', today())->count() * 2,
            'uptime' => '99.9%',
            'response_time' => '<1s',
        ];

        // Chart Data
        $chart_data = [
            'user_labels' => User::selectRaw('DATE_FORMAT(created_at, "%b") as month')
                ->groupBy('month')
                ->pluck('month')
                ->toArray() ?: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],

            'user_data' => User::selectRaw('DATE_FORMAT(created_at, "%b") as month, COUNT(*) as count')
                ->groupBy('month')
                ->pluck('count')
                ->toArray() ?: [2, 5, 8, 12, 18, 25],

            'conversation_labels' => collect(range(6, 0))->map(function ($i) {
                return now()->subDays($i)->format('D');
            })->toArray(),
            'conversation_data' => collect(range(6, 0))->map(function ($i) {
                return Conversation::whereDate('created_at', now()->subDays($i))->count();
            })->toArray(),
        ];

        // Recent Users
        $recent_users = User::latest()->take(5)->get();

        // Top Chatbots
        $top_chatbots = Chatbot::with('user')
            ->withCount('conversations')
            ->orderBy('conversations_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'chart_data', 'recent_users', 'top_chatbots'));
    }

    public function users()
    {
        $total_users = User::count();
        $super_admins = User::where('is_super_admin', true)->count();
        $regular_users = User::where('is_super_admin', false)->count();
        $new_this_month = User::whereMonth('created_at', now()->month)->count();

        return view('admin.users', compact('total_users', 'super_admins', 'regular_users', 'new_this_month'));
    }

    public function chatbots()
    {
        $chatbots = Chatbot::with(['user.plan'])->withCount('conversations')->get();
        return view('admin.chatbots', compact('chatbots'));
    }

    public function payments()
    {
        // Revenue Stats
        $total_revenue = \App\Models\Subscription::where('status', 'active')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->sum('plans.price_inr');

        $monthly_revenue = \App\Models\Subscription::where('status', 'active')
            ->whereMonth('subscriptions.created_at', now()->month)
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->sum('plans.price_inr');

        $total_subscriptions = \App\Models\Subscription::where('status', 'active')->count();
        $new_subscriptions_this_month = \App\Models\Subscription::whereMonth('created_at', now()->month)->count();

        // Subscription breakdown by plan
        $subscriptions_by_plan = \App\Models\Subscription::where('status', 'active')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->selectRaw('plans.name, COUNT(*) as count, SUM(plans.price_inr) as revenue')
            ->groupBy('plans.id', 'plans.name')
            ->get();

        // Recent transactions
        $recent_transactions = \App\Models\Subscription::with(['user', 'plan'])
            ->latest()
            ->take(10)
            ->get();

        // Revenue trend (last 6 months)
        $revenue_trend = \App\Models\Subscription::selectRaw('DATE_FORMAT(created_at, "%b") as month, COUNT(*) as count, MIN(created_at) as min_date')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('min_date')
            ->get();

        return view('admin.payments', compact(
            'total_revenue',
            'monthly_revenue',
            'total_subscriptions',
            'new_subscriptions_this_month',
            'subscriptions_by_plan',
            'recent_transactions',
            'revenue_trend'
        ));
    }
}


