<?php

namespace App\Livewire\Widget;

use App\Models\Chatbot;
use App\Services\ChatbotService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class ChatBox extends Component
{
    use WithFileUploads;
    public Chatbot $chatbot;
    public string $visitorId;
    public string $message = '';
    public array $messages = [];
    public bool $loading = false;
    public array $suggestedQuestions = [];
    public string $languageSearch = '';
    public $fileUpload;
    public $audioUpload;
    public $uploadedFile = null;
    public $transcriptEmail = '';
    public $showEmailModal = false;

    // Lead Capture
    public $showLeadCaptureModal = false;
    public $contactName = '';
    public $contactPhone = '';
    public $uploadedFileName = '';
    public $showEmojiPicker = false;
    public bool $isSoundOn = false;
    public bool $isAlertSoundOn = true;
    public string $selectedLanguage = 'en';
    public array $languages = [
        'en' => ['name' => 'English', 'voice' => 'en-US'],
        'hi' => ['name' => 'Hindi', 'voice' => 'hi-IN'],
        'bn' => ['name' => 'Bengali', 'voice' => 'bn-BD'],
        'te' => ['name' => 'Telugu', 'voice' => 'te-IN'],
        'mr' => ['name' => 'Marathi', 'voice' => 'mr-IN'],
        'ta' => ['name' => 'Tamil', 'voice' => 'ta-IN'],
        'gu' => ['name' => 'Gujarati', 'voice' => 'gu-IN'],
        'kn' => ['name' => 'Kannada', 'voice' => 'kn-IN'],
        'ml' => ['name' => 'Malayalam', 'voice' => 'ml-IN'],
        'pa' => ['name' => 'Punjabi', 'voice' => 'pa-IN'],
        'es' => ['name' => 'Spanish', 'voice' => 'es-ES'],
        'fr' => ['name' => 'French', 'voice' => 'fr-FR'],
        'de' => ['name' => 'German', 'voice' => 'de-DE'],
        'it' => ['name' => 'Italian', 'voice' => 'it-IT'],
        'pt' => ['name' => 'Portuguese', 'voice' => 'pt-PT'],
        'ru' => ['name' => 'Russian', 'voice' => 'ru-RU'],
        'ja' => ['name' => 'Japanese', 'voice' => 'ja-JP'],
        'ko' => ['name' => 'Korean', 'voice' => 'ko-KR'],
        'zh' => ['name' => 'Chinese', 'voice' => 'zh-CN'],
        'ar' => ['name' => 'Arabic', 'voice' => 'ar-SA'],
        'tr' => ['name' => 'Turkish', 'voice' => 'tr-TR'],
        'vi' => ['name' => 'Vietnamese', 'voice' => 'vi-VN'],
        'th' => ['name' => 'Thai', 'voice' => 'th-TH'],
        'id' => ['name' => 'Indonesian', 'voice' => 'id-ID'],
    ];

    protected $rules = [
        'fileUpload' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx',
        'audioUpload' => 'nullable|file|max:10240|mimes:webm,mp3,wav,m4a,ogg',
    ];

    protected ChatbotService $chatbotService;

    public function boot(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
        // The session settings are now loaded in mount
    }

    public function mount(Chatbot $chatbot)
    {
        $this->chatbot = $chatbot;
        $this->languages = Config::get('languages') ?? []; // Load from config or empty

        // Generate or retrieve base visitor ID
        if (!session()->has('visitor_id')) {
            session(['visitor_id' => Str::uuid()->toString()]);
        }
        $this->visitorId = session('visitor_id');

        // Restore session settings
        if (session()->has('chatbot_language')) {
            $this->selectedLanguage = session('chatbot_language');
        }
        if (session()->has('chatbot_sound_on')) {
            $this->isSoundOn = session('chatbot_sound_on');
        }
        if (session()->has('chatbot_alert_sound_on')) {
            $this->isAlertSoundOn = session('chatbot_alert_sound_on');
        }

        // Get current domain
        $currentDomain = $this->extractDomain(
            request()->header('Origin')
            ?? request()->header('Referer')
            ?? request()->server('HTTP_REFERER')
            ?? 'unknown'
        );

        // Check for Lead Capture FIRST
        // Check for Lead Capture removed from mount to allow lazy verification

        // Load existing conversation
        $this->loadMessages();

        // Set suggested questions
        $this->suggestedQuestions = [
            'Is there a free trial available?',
            'How is my data protected?',
            'Can I cancel my subscription at any time?',
            'What\'s New?',
        ];

        \Log::info('ChatBox mounted', [
            'chatbot_id' => $chatbot->id,
            'visitor_id' => $this->visitorId,
        ]);
    }

    /**
     * Validate if the current request is from an allowed domain
     */
    protected function validateDomain(array $allowedDomains): void
    {
        $origin = request()->header('Origin')
            ?? request()->header('Referer')
            ?? request()->server('HTTP_REFERER');

        if (!$origin) {
            abort(403, 'Domain validation failed: No origin detected');
        }

        // Extract domain from origin/referer
        $requestDomain = $this->extractDomain($origin);

        // Allow if the request is from the app's own domain (e.g. pop-out widget or internal iframe)
        $appDomain = $this->extractDomain(config('app.url'));
        if ($this->normalizeDomain($requestDomain) === $this->normalizeDomain($appDomain)) {
            return;
        }

        // Normalize and check against allowed domains
        $isAllowed = false;
        foreach ($allowedDomains as $allowedDomain) {
            // Robustly handle if user entered full URL like https://example.com
            $allowedHost = $this->extractDomain($allowedDomain);
            $normalizedAllowed = $this->normalizeDomain($allowedHost);

            if ($this->normalizeDomain($requestDomain) === $normalizedAllowed) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            abort(403, 'This chatbot is not authorized for this domain');
        }
    }

    /**
     * Extract domain from URL
     */
    protected function extractDomain(string $url): string
    {
        $parsed = parse_url($url);
        return $parsed['host'] ?? $url;
    }

    /**
     * Normalize domain (remove www, convert to lowercase)
     */
    protected function normalizeDomain(string $domain): string
    {
        $domain = strtolower(trim($domain));
        $domain = preg_replace('/^www\./', '', $domain);
        return $domain;
    }

    public function loadMessages()
    {
        $conversation = $this->chatbotService->getOrCreateConversation(
            $this->chatbot,
            $this->visitorId
        );

        $this->messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($msg) => [
                'role' => $msg->role,
                'content' => $msg->content,
                'image_url' => $msg->image_url,
                'time' => $msg->created_at->format('g:i A'),
            ])
            ->toArray();
    }

    public function sendMessage()
    {
        if (trim($this->message) === '') {
            return;
        }

        // Check if user needs to provide verification first
        if ($this->shouldShowLeadCapture()) {
            $this->showLeadCaptureModal = true;
            return;
        }

        // Block sending if lead capture is active (modal is open)
        if ($this->showLeadCaptureModal) {
            return;
        }

        // Check limits
        if ($this->chatbot->user->hasReachedMessageLimit()) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => 'This chatbot has reached its monthly message limit. Please try again later or contact the site owner.',
                'time' => now()->format('g:i A'),
            ];
            $this->message = '';
            return;
        }

        $userMessage = $this->message;
        $this->message = '';
        $this->loading = true;

        // Add user message to UI immediately
        $this->messages[] = [
            'role' => 'user',
            'content' => $userMessage,
            'image_url' => $this->uploadedFile ? $this->uploadedFile->temporaryUrl() : null,
            'time' => now()->format('g:i A'),
        ];

        // Log Activity
        $this->chatbot->user->activities()->create([
            'chatbot_id' => $this->chatbot->id,
            'type' => 'message_sent',
            'message' => "Message sent to \"{$this->chatbot->name}\"",
            'data' => [
                'content_preview' => Str::limit($userMessage, 50),
                'has_image' => (bool) $this->uploadedFile,
            ],
        ]);

        // Trigger AI processing in next request
        $this->dispatch('message-sent', userMessage: $userMessage);
    }

    public function sendVoiceNote()
    {
        if (!$this->audioUpload) {
            return;
        }

        // Check if plan allows voice messages
        if (!$this->chatbot->user->plan->allows_voice_messages) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Voice messages are not available on your current plan. Please upgrade to use this feature.'
            ]);
            $this->audioUpload = null;
            $this->loading = false;
            return;
        }

        $this->loading = true;
        $this->dispatch('notify', ['type' => 'info', 'message' => 'Processing voice message...']);

        try {
            // Transcribe audio
            $transcribedText = $this->chatbotService->transcribeAudio($this->audioUpload);

            if (!$transcribedText) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Could not transcribe audio.']);
                $this->loading = false;
                return;
            }

            // Add user "audio" message to UI (with transcript for now)
            $this->messages[] = [
                'role' => 'user',
                'content' => $transcribedText,
                'time' => now()->format('g:i A'),
            ];

            // Trigger AI processing with the transcribed text
            $this->dispatch('message-sent', userMessage: $transcribedText);

        } catch (\Exception $e) {
            \Log::error('Voice Note error', ['error' => $e->getMessage()]);
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Voice message failed.']);
        } finally {
            $this->audioUpload = null;
        }
    }

    public function selectSuggestedQuestion(string $question)
    {
        // Remove emoji and trim
        $cleanQuestion = preg_replace('/[\x{1F300}-\x{1F9FF}]/u', '', $question);
        $cleanQuestion = trim($cleanQuestion);

        $this->message = $cleanQuestion;
        $this->sendMessage();
    }

    public function addEmoji(string $emoji)
    {
        $this->message .= $emoji;
        $this->showEmojiPicker = false; // Close picker after selection
    }

    public function toggleEmojiPicker()
    {
        $this->showEmojiPicker = !$this->showEmojiPicker;
    }

    public function toggleSound()
    {
        $this->isSoundOn = !$this->isSoundOn;
        session(['chatbot_sound_on' => $this->isSoundOn]);

        $status = $this->isSoundOn ? 'enabled' : 'disabled';
        $this->dispatch('sound-toggled', status: $status);
    }

    public function toggleAlertSound()
    {
        $this->isAlertSoundOn = !$this->isAlertSoundOn;
        session(['chatbot_alert_sound_on' => $this->isAlertSoundOn]);
    }

    public function setLanguage(string $langCode)
    {
        if (isset($this->languages[$langCode])) {
            $this->selectedLanguage = $langCode;
            session(['chatbot_language' => $langCode]);

            // Dispatch event for frontend to update voice/recognition
            $this->dispatch('language-changed', [
                'code' => $langCode,
                'voice' => $this->languages[$langCode]['voice']
            ]);

            $this->dispatch('notify', ['type' => 'success', 'message' => "Language changed to " . $this->languages[$langCode]['name']]);
        }
    }

    public function emailTranscript()
    {
        if (empty($this->messages)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'No conversation to email!']);
            return;
        }

        $this->showEmailModal = true;
    }

    public function sendTranscript()
    {
        $this->validate([
            'transcriptEmail' => 'required|email'
        ]);

        try {
            \Mail::to($this->transcriptEmail)->send(new \App\Mail\ChatTranscript($this->messages, $this->chatbot));

            $this->showEmailModal = false;
            $this->transcriptEmail = '';
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Transcript sent to your email!']);

        } catch (\Exception $e) {
            \Log::error('Email Transcript Error: ' . $e->getMessage());
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Failed to send email. Please try again.']);
        }
    }

    public function updatedFileUpload()
    {
        // Check if plan allows image uploads
        if ($this->fileUpload && !$this->chatbot->user->plan->allows_image_upload) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Image uploads are not available on your current plan. Please upgrade to use this feature.'
            ]);
            $this->fileUpload = null;
            $this->uploadedFile = null;
            $this->uploadedFileName = '';
            return;
        }

        $this->validate();

        if ($this->fileUpload) {
            $this->uploadedFile = $this->fileUpload;
            $this->uploadedFileName = $this->fileUpload->getClientOriginalName();
        }
    }

    public function removeFile()
    {
        $this->uploadedFile = null;
        $this->uploadedFileName = '';
        $this->fileUpload = null;
    }

    #[\Livewire\Attributes\On('message-sent')]
    public function generateResponse(string $userMessage)
    {
        try {
            $conversation = $this->chatbotService->getOrCreateConversation(
                $this->chatbot,
                $this->visitorId
            );

            // Pass the uploaded file and language to the service
            $response = $this->chatbotService->sendMessage(
                $this->chatbot,
                $conversation,
                $userMessage,
                $this->uploadedFile, // Pass the file for image analysis
                $this->selectedLanguage // Pass selected language
            );

            // If null, it means manual mode is active, do nothing
            if ($response) {
                // Add AI response
                $this->messages[] = [
                    'role' => 'assistant',
                    'content' => $response->content,
                    'time' => $response->created_at->format('g:i A'),
                ];

                if ($this->isSoundOn) {
                    $this->dispatch('speak', text: $response->content, lang: $this->languages[$this->selectedLanguage]['voice']);
                }

                if ($this->isAlertSoundOn) {
                    $this->dispatch('play-ding');
                }

                // Increment usage
                $this->chatbot->user->incrementMessageCount();
            }

            // Clear uploaded file after sending
            $this->removeFile();
        } catch (\Exception $e) {
            $this->messages[] = [
                'role' => 'assistant',
                'content' => 'Sorry, something went wrong. Please try again.',
                'time' => now()->format('g:i A'),
            ];

            \Log::error('ChatBox generateResponse error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        $this->loading = false;
    }

    public function shouldShowLeadCapture()
    {
        // Check if the plan allows lead capture
        if (!$this->chatbot->user->plan?->has_lead_capture) {
            return false;
        }

        $conversation = \App\Models\Conversation::where('chatbot_id', $this->chatbot->id)
            ->where('visitor_id', $this->visitorId)
            ->first();

        // If validation needed (no phone number)
        if (!$conversation || empty($conversation->visitor_phone)) {
            return true;
        }

        return false;
    }

    public function checkLeadCapture()
    {
        // Legacy method kept for compatibility or manual checks if needed
        if ($this->shouldShowLeadCapture()) {
            $this->showLeadCaptureModal = true;
        }
    }

    public function submitLeadCapture()
    {
        $this->validate([
            'contactName' => 'required|string|max:255',
            'contactPhone' => 'required|string|max:20',
        ]);

        $conversation = $this->chatbotService->getOrCreateConversation(
            $this->chatbot,
            $this->visitorId
        );

        $conversation->update([
            'visitor_name' => $this->contactName,
            'visitor_phone' => $this->contactPhone,
        ]);

        $this->showLeadCaptureModal = false;

        // Auto-send message if one was pending
        if (!empty($this->message)) {
            $this->sendMessage();
        }
    }

    public function render()
    {
        return view('livewire.widget.chat-box');
    }
}

