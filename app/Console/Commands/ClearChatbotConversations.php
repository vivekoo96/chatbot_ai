<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use Illuminate\Console\Command;

class ClearChatbotConversations extends Command
{
    protected $signature = 'chatbot:clear-conversations {chatbot_id?}';
    protected $description = 'Clear all conversations for a chatbot (useful for testing)';

    public function handle()
    {
        $chatbotId = $this->argument('chatbot_id');

        if ($chatbotId) {
            $count = Conversation::where('chatbot_id', $chatbotId)->delete();
            $this->info("✓ Cleared {$count} conversations for chatbot ID {$chatbotId}");
        } else {
            if (!$this->confirm('This will delete ALL conversations. Are you sure?')) {
                return 0;
            }
            $count = Conversation::query()->delete();
            $this->info("✓ Cleared {$count} total conversations");
        }

        return 0;
    }
}
