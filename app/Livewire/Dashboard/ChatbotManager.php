<?php

namespace App\Livewire\Dashboard;

use App\Models\Chatbot;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatbotManager extends Component
{
    public $name = '';
    public $system_prompt = '';
    public $theme_color = '#3B82F6';
    public $allowed_domains = '';
    public $website_url = '';
    public $whatsapp_number = '';
    public $auto_crawl_enabled = false;
    public $showCreateForm = false;
    public $showEditModal = false;
    public $editingId = null;
    public $showInstallModal = false;
    public $selectedBot = null;
    public $manual_content = '';
    public $knowledgeEntries = [];
    public $training_file = null;

    // Conversation Viewing
    public $activeTab = 'assistants'; // assistants, conversations
    public $viewMode = 'list'; // list, conversations, messages
    public $selectedChatbotId = null;
    public $selectedConversationId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'system_prompt' => 'nullable|string',
        'theme_color' => 'required|string|max:7',
        'allowed_domains' => 'nullable|string',
        'website_url' => 'nullable|url',
        'whatsapp_number' => 'nullable|string|max:20',
        'training_file' => 'nullable|file|max:10240|mimes:txt,pdf,doc,docx',
    ];

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        if ($tab === 'assistants') {
            $this->viewMode = 'list';
        } else {
            $this->viewMode = 'conversations';
            $this->selectedChatbotId = null; // Clear to show all
        }
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;

        if ($this->showCreateForm) {
            if (empty($this->system_prompt)) {
                $owner = Auth::user()->getOwner();
                if ($owner->business_name) {
                    $this->system_prompt = "You are a helpful AI assistant for {$owner->business_name}, a company in the {$owner->industry} industry based in {$owner->country}. Your goal is to provide accurate and personalized support to our customers while reflecting our brand values.";
                }
            }
            if (empty($this->website_url)) {
                $this->website_url = config('app.url');
            }
        }
    }

    public function create()
    {
        $this->validate();

        if (Auth::user()->isTeamViewer()) {
            session()->flash('error', 'Viewers cannot create chatbots.');
            return;
        }

        $owner = Auth::user()->getOwner();

        if ($owner->hasReachedChatbotLimit()) {
            session()->flash('error', 'You have reached your chatbot limit. Please upgrade your plan.');
            return;
        }

        // Parse allowed domains
        $allowedDomains = null;
        if (!empty($this->allowed_domains)) {
            $allowedDomains = array_map('trim', explode(',', $this->allowed_domains));
            $allowedDomains = array_filter($allowedDomains); // Remove empty values
        }

        $chatbot = $owner->chatbots()->create([
            'name' => $this->name,
            'whatsapp_number' => $this->whatsapp_number,
            'system_prompt' => $this->system_prompt,
            'theme_color' => $this->theme_color,
            'allowed_domains' => $allowedDomains,
            'detected_website_url' => $this->website_url ?: config('app.url'),
        ]);

        // Handle training file upload
        if ($this->training_file) {
            $ext = $this->training_file->getClientOriginalExtension();
            $content = '';
            if ($ext === 'txt') {
                $content = file_get_contents($this->training_file->getRealPath());
            } elseif ($ext === 'pdf') {
                // Use a PDF parser library if available
                // $content = ...
            } elseif (in_array($ext, ['doc', 'docx'])) {
                // Use a DOC/DOCX parser library if available
                // $content = ...
            }
            if (!empty($content)) {
                \App\Models\KnowledgeEntry::create([
                    'chatbot_id' => $chatbot->id,
                    'content' => $content,
                    'category' => 'upload',
                ]);
            }
        }

        $this->reset(['name', 'whatsapp_number', 'system_prompt', 'theme_color', 'allowed_domains', 'website_url', 'showCreateForm', 'training_file']);
        session()->flash('message', 'Chatbot created successfully!');
    }

    public function delete($id)
    {
        if (Auth::user()->isTeamViewer()) {
            session()->flash('error', 'Viewers cannot delete chatbots.');
            return;
        }

        $owner = Auth::user()->getOwner();
        Chatbot::where('id', $id)
            ->where('user_id', $owner->id)
            ->delete();

        session()->flash('message', 'Chatbot deleted successfully!');
    }

    public function showInstall($id)
    {
        $owner = Auth::user()->getOwner();
        $this->selectedBot = Chatbot::where('id', $id)
            ->where('user_id', $owner->id)
            ->firstOrFail();

        $this->showInstallModal = true;
    }

    public function toggleActive($id)
    {
        if (Auth::user()->isTeamViewer()) {
            session()->flash('error', 'Viewers cannot toggle chatbots.');
            return;
        }

        $owner = Auth::user()->getOwner();
        $chatbot = Chatbot::where('id', $id)
            ->where('user_id', $owner->id)
            ->firstOrFail();

        $chatbot->update(['is_active' => !$chatbot->is_active]);
    }

    public function edit($id)
    {
        $owner = Auth::user()->getOwner();
        $chatbot = Chatbot::where('id', $id)
            ->where('user_id', $owner->id)
            ->firstOrFail();

        $this->editingId = $chatbot->id;
        $this->name = $chatbot->name;
        $this->system_prompt = $chatbot->system_prompt;
        $this->theme_color = $chatbot->theme_color;
        $this->allowed_domains = is_array($chatbot->allowed_domains)
            ? implode(', ', $chatbot->allowed_domains)
            : '';
        $this->whatsapp_number = $chatbot->whatsapp_number ?? '';
        $this->website_url = $chatbot->detected_website_url ?: config('app.url');
        $this->auto_crawl_enabled = $chatbot->auto_crawl_enabled ?? true;
        $this->loadKnowledgeEntries();
        $this->showEditModal = true;
    }

    public function loadKnowledgeEntries()
    {
        if ($this->editingId) {
            $this->knowledgeEntries = \App\Models\KnowledgeEntry::where('chatbot_id', $this->editingId)
                ->whereNull('website_page_id') // Manual entries don't have a website_page_id
                ->latest()
                ->get();
        }
    }

    public function addManualKnowledge()
    {
        $this->validate([
            'manual_content' => 'required|string|min:10',
        ]);

        \App\Models\KnowledgeEntry::create([
            'chatbot_id' => $this->editingId,
            'content' => $this->manual_content,
            'category' => 'manual',
        ]);

        $this->manual_content = '';
        $this->loadKnowledgeEntries();
        session()->flash('message', 'Manual knowledge added successfully!');
    }

    public function deleteKnowledgeEntry($id)
    {
        \App\Models\KnowledgeEntry::where('id', $id)
            ->where('chatbot_id', $this->editingId)
            ->delete();

        $this->loadKnowledgeEntries();
        session()->flash('message', 'Knowledge entry removed.');
    }

    public function update()
    {
        if (Auth::user()->isTeamViewer()) {
            session()->flash('error', 'Viewers cannot edit chatbots.');
            return;
        }

        $this->validate();

        $owner = Auth::user()->getOwner();
        $chatbot = Chatbot::where('id', $this->editingId)
            ->where('user_id', $owner->id)
            ->firstOrFail();

        // Parse allowed domains
        $allowedDomains = null;
        if (!empty($this->allowed_domains)) {
            $allowedDomains = array_map('trim', explode(',', $this->allowed_domains));
            $allowedDomains = array_filter($allowedDomains);
        }

        $chatbot->update([
            'name' => $this->name,
            'whatsapp_number' => $this->whatsapp_number,
            'system_prompt' => $this->system_prompt,
            'theme_color' => $this->theme_color,
            'allowed_domains' => $allowedDomains,
            'detected_website_url' => $this->website_url ?: config('app.url'),
            'auto_crawl_enabled' => $this->auto_crawl_enabled,
        ]);

        // Handle training file upload during update
        if ($this->training_file) {
            $ext = $this->training_file->getClientOriginalExtension();
            $content = '';
            if ($ext === 'txt') {
                $content = file_get_contents($this->training_file->getRealPath());
            } elseif ($ext === 'pdf') {
                // Use a PDF parser library if available
                // $content = ...
            } elseif (in_array($ext, ['doc', 'docx'])) {
                // Use a DOC/DOCX parser library if available
                // $content = ...
            }
            if (!empty($content)) {
                \App\Models\KnowledgeEntry::create([
                    'chatbot_id' => $chatbot->id,
                    'content' => $content,
                    'category' => 'upload',
                ]);
            }
        }

        $this->reset(['name', 'whatsapp_number', 'system_prompt', 'theme_color', 'allowed_domains', 'website_url', 'auto_crawl_enabled', 'showEditModal', 'editingId', 'training_file']);
        session()->flash('message', 'Chatbot updated successfully!');
    }

    public function triggerCrawl($id)
    {
        $owner = Auth::user()->getOwner();
        $chatbot = Chatbot::where('id', $id)
            ->where('user_id', $owner->id)
            ->firstOrFail();

        if (empty($chatbot->detected_website_url)) {
            session()->flash('error', 'Please set a website URL first.');
            return;
        }

        $chatbot->update(['crawl_status' => 'pending']);
        \App\Jobs\CrawlWebsiteJob::dispatch($chatbot->id, $chatbot->detected_website_url);

        session()->flash('message', 'Website crawl started! This may take a few minutes.');
    }

    public function showConversations($id)
    {
        $this->selectedChatbotId = $id;
        $this->viewMode = 'conversations';
    }

    public function showMessages($id)
    {
        $this->selectedConversationId = $id;
        $this->viewMode = 'messages';
    }

    public function backToList()
    {
        $this->viewMode = 'list';
        $this->activeTab = 'assistants';
        $this->selectedChatbotId = null;
        $this->selectedConversationId = null;
    }

    public function backToConversations()
    {
        $this->viewMode = 'conversations';
        $this->selectedConversationId = null;
    }

    public function render()
    {
        $user = Auth::user();
        $owner = $user->getOwner();
        $chatbots = $owner->chatbots()->withCount('conversations')->get();

        $conversations = [];
        $messages = [];
        $selectedBotName = '';

        $selectedConversation = null;

        if ($this->viewMode === 'conversations') {
            if ($this->selectedChatbotId) {
                $bot = Chatbot::where('id', $this->selectedChatbotId)
                    ->where('user_id', $owner->id)
                    ->firstOrFail();
                $selectedBotName = $bot->name;
                $conversations = $bot->conversations()->withCount('messages')->latest()->get();
            } else {
                $selectedBotName = 'All Assistants';
                $conversations = \App\Models\Conversation::whereHas('chatbot', function ($q) use ($owner) {
                    $q->where('user_id', $owner->id);
                })->withCount('messages')->latest()->get();
            }
        }

        if ($this->viewMode === 'messages' && $this->selectedConversationId) {
            $selectedConversation = \App\Models\Conversation::with('chatbot')
                ->where('id', $this->selectedConversationId)
                ->whereHas('chatbot', function ($q) use ($owner) {
                    $q->where('user_id', $owner->id);
                })
                ->firstOrFail();
            $selectedBotName = $selectedConversation->chatbot->name;
            $messages = $selectedConversation->messages()->oldest()->get();
        }

        return view('livewire.dashboard.chatbot-manager', [
            'chatbots' => $chatbots,
            'conversations' => $conversations,
            'messages' => $messages,
            'selectedBotName' => $selectedBotName,
            'selectedConversation' => $selectedConversation,
        ]);
    }
}

