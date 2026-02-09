<?php

namespace App\Livewire\Dashboard;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LiveChat extends Component
{
    public $conversations = [];
    public $selectedConversation = null;
    public $conversationMessages = [];
    public $newMessage = '';
    public $filter = 'all'; // all, active, manual

    // Enable deep linking
    public $conversationId = null;
    protected $queryString = ['conversationId'];

    // Poll for new messages every 3 seconds if a conversation is selected
    public function getListeners()
    {
        return [
            'echo-private:conversations.' . ($this->selectedConversation->id ?? '0') . ',MessageSent' => 'refreshMessages',
        ];
    }

    public function mount()
    {
        $this->loadConversations();

        // Deep link selection
        if ($this->conversationId) {
            $this->selectConversation($this->conversationId);
        }
    }

    public function loadConversations()
    {
        $owner = Auth::user()->getOwner();

        $query = Conversation::whereHas('chatbot', function ($q) use ($owner) {
            $q->where('user_id', $owner->id);
        })
            ->with([
                'chatbot',
                'messages' => function ($q) {
                    $q->latest()->limit(1);
                }
            ])
            ->orderBy('updated_at', 'desc');

        if ($this->filter === 'manual') {
            $query->where('is_manual_mode', true);
        }

        $this->conversations = $query->take(20)->get();
    }

    public function selectConversation($id)
    {
        $owner = Auth::user()->getOwner();

        $this->selectedConversation = Conversation::with('chatbot')
            ->whereHas('chatbot', function ($q) use ($owner) {
                $q->where('user_id', $owner->id);
            })
            ->findOrFail($id);

        $this->loadMessages();
    }

    public function loadMessages()
    {
        if ($this->selectedConversation) {
            $this->conversationMessages = $this->selectedConversation->messages()
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }

    public function toggleManualMode()
    {
        if (Auth::user()->isTeamViewer()) {
            session()->flash('error', 'Viewers cannot toggle manual mode.');
            return;
        }

        if ($this->selectedConversation) {
            $this->selectedConversation->update([
                'is_manual_mode' => !$this->selectedConversation->is_manual_mode
            ]);
        }
    }

    public function sendMessage()
    {
        if (Auth::user()->isTeamViewer()) {
            session()->flash('error', 'Viewers cannot send messages.');
            return;
        }

        $this->validate([
            'newMessage' => 'required|string|max:1000'
        ]);

        if ($this->selectedConversation) {
            $this->selectedConversation->messages()->create([
                'role' => 'assistant', // Operator acts as assistant
                'content' => $this->newMessage,
                'tokens' => 0,
            ]);

            $this->newMessage = '';
            $this->loadMessages();
        }
    }

    public function render()
    {
        // Poll for list updates
        if (!$this->selectedConversation) {
            $this->loadConversations();
        }

        return view('livewire.dashboard.live-chat')->layout('layouts.app');
    }
}
