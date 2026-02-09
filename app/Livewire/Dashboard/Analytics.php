<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Analytics extends Component
{
    public $period = '7days'; // 7days, 30days, 90days

    public function render()
    {
        $user = auth()->user();
        $owner = $user->getOwner();

        // Get date range based on period
        $startDate = match ($this->period) {
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            '90days' => now()->subDays(90),
            default => now()->subDays(7),
        };

        // Conversations over time
        $conversationTrend = DB::table('conversations')
            ->join('chatbots', 'conversations.chatbot_id', '=', 'chatbots.id')
            ->when(!$user->is_super_admin, function ($query) use ($owner) {
                return $query->where('chatbots.user_id', $owner->id);
            })
            ->where('conversations.created_at', '>=', $startDate)
            ->select(DB::raw('DATE(conversations.created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Messages over time
        $messageTrend = DB::table('messages')
            ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
            ->join('chatbots', 'conversations.chatbot_id', '=', 'chatbots.id')
            ->when(!$user->is_super_admin, function ($query) use ($owner) {
                return $query->where('chatbots.user_id', $owner->id);
            })
            ->where('messages.created_at', '>=', $startDate)
            ->select(DB::raw('DATE(messages.created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Chatbot performance
        $chatbotStats = DB::table('chatbots')
            ->leftJoin('conversations', 'chatbots.id', '=', 'conversations.chatbot_id')
            ->leftJoin('messages', 'conversations.id', '=', 'messages.conversation_id')
            ->when(!$user->is_super_admin, function ($query) use ($owner) {
                return $query->where('chatbots.user_id', $owner->id);
            })
            ->select(
                'chatbots.name',
                DB::raw('COUNT(DISTINCT CASE WHEN conversations.created_at >= "' . $startDate . '" THEN conversations.id END) as conversation_count'),
                DB::raw('COUNT(CASE WHEN messages.created_at >= "' . $startDate . '" THEN messages.id END) as message_count')
            )
            ->groupBy('chatbots.id', 'chatbots.name')
            ->get();

        // User vs Bot messages
        $messagesByRole = DB::table('messages')
            ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
            ->join('chatbots', 'conversations.chatbot_id', '=', 'chatbots.id')
            ->when(!$user->is_super_admin, function ($query) use ($owner) {
                return $query->where('chatbots.user_id', $owner->id);
            })
            ->where('messages.created_at', '>=', $startDate)
            ->select('messages.role', DB::raw('COUNT(*) as count'))
            ->groupBy('messages.role')
            ->get();

        // Total stats
        $totalConversations = DB::table('conversations')
            ->join('chatbots', 'conversations.chatbot_id', '=', 'chatbots.id')
            ->when(!$user->is_super_admin, function ($query) use ($owner) {
                return $query->where('chatbots.user_id', $owner->id);
            })
            ->where('conversations.created_at', '>=', $startDate)
            ->count();

        $totalMessages = DB::table('messages')
            ->join('conversations', 'messages.conversation_id', '=', 'conversations.id')
            ->join('chatbots', 'conversations.chatbot_id', '=', 'chatbots.id')
            ->when(!$user->is_super_admin, function ($query) use ($owner) {
                return $query->where('chatbots.user_id', $owner->id);
            })
            ->where('messages.created_at', '>=', $startDate)
            ->count();

        $avgMessagesPerConversation = $totalConversations > 0
            ? round($totalMessages / $totalConversations, 1)
            : 0;

        return view('livewire.dashboard.analytics', [
            'conversationTrend' => $conversationTrend,
            'messageTrend' => $messageTrend,
            'chatbotStats' => $chatbotStats,
            'messagesByRole' => $messagesByRole,
            'totalConversations' => $totalConversations,
            'totalMessages' => $totalMessages,
            'avgMessagesPerConversation' => $avgMessagesPerConversation,
        ]);
    }
}
