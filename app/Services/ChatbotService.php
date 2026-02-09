<?php

namespace App\Services;

use App\Models\Chatbot;
use App\Models\Conversation;
use App\Models\Message;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    protected RAGService $ragService;
    protected WhatsAppNotificationService $whatsAppService;

    public function __construct(RAGService $ragService, WhatsAppNotificationService $whatsAppService)
    {
        $this->ragService = $ragService;
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Send a message to OpenAI and store the conversation
     */
    public function sendMessage(Chatbot $chatbot, Conversation $conversation, string $userMessage, $imageFile = null, string $language = 'en'): ?Message
    {
        try {
            // Save user message
            $userMsg = $conversation->messages()->create([
                'role' => 'user',
                'content' => $userMessage,
                'image_path' => $imageFile ? $imageFile->store('chat-images', 'public') : null,
            ]);

            // Notify admin if this is the first message of a new conversation
            if ($conversation->messages()->where('role', 'user')->count() === 1) {
                $this->whatsAppService->notifyAdminOfNewConversation($chatbot, $conversation, $userMessage);
            }

            // If in manual mode (Live Chat), skip AI processing
            if ($conversation->is_manual_mode) {
                return null;
            }

            // Automatically select AI model based on input type
            $modelSelector = app(AIModelSelector::class);
            $input = [
                'message' => $userMessage,
                'image' => $imageFile,
            ];

            $model = $modelSelector->selectModel($input);
            $modelType = $modelSelector->getModelCapabilities($model)['type'];

            // Validate quota before processing
            $user = $chatbot->user;
            $quotaCheck = $modelSelector->validateQuota($user, $modelType);

            if (!$quotaCheck['has_quota']) {
                return $conversation->messages()->create([
                    'role' => 'assistant',
                    'content' => "You've reached your {$quotaCheck['type']} limit. Please upgrade your plan or purchase add-ons to continue.",
                    'tokens' => 0,
                ]);
            }

            // Prepare conversation history for OpenAI
            $messages = $this->prepareMessages($chatbot, $conversation, $userMessage, $imageFile, $language);

            // Check if context is strictly from website/uploaded files
            $contextOnly = false;
            if (isset($messages[0]['role']) && $messages[0]['role'] === 'system') {
                $systemPrompt = $messages[0]['content'];
                // If system prompt contains only default text, no website/uploaded context
                if (strpos($systemPrompt, 'Uploaded & Custom Knowledge Base:') !== false || strpos($systemPrompt, 'Website Information (Crawled Content):') !== false) {
                    $contextOnly = true;
                }
            }

            if (!$contextOnly) {
                // No relevant data found, reply with restricted message
                return $conversation->messages()->create([
                    'role' => 'assistant',
                    'content' => 'Sorry, I can only answer questions about this website or its uploaded documents.',
                    'tokens' => 0,
                ]);
            }

            // Call OpenAI API with automatically selected model
            $response = OpenAI::chat()->create([
                'model' => $model,
                'messages' => $messages,
                'max_completion_tokens' => 500,
                'temperature' => 0.7,
            ]);

            // Extract AI response
            $aiContent = $response->choices[0]->message->content ?? null;
            $tokensUsed = $response->usage->totalTokens ?? 0;

            if (!$aiContent) {
                throw new \Exception('No response from OpenAI');
            }

            // Consume quota (add-ons first, then plan)
            $modelSelector->consumeQuota($user, $modelType, 1);

            // Save assistant message
            $assistantMsg = $conversation->messages()->create([
                'role' => 'assistant',
                'content' => $aiContent,
                'tokens' => $tokensUsed,
            ]);

            Log::info('AI Response Generated', [
                'model' => $model,
                'type' => $modelType,
                'tokens' => $tokensUsed,
                'quota_remaining' => $quotaCheck['available'] - 1
            ]);

            return $assistantMsg;

        } catch (\Exception $e) {
            Log::error('ChatbotService Error: ' . $e->getMessage());

            // Return a friendly fallback message
            return $conversation->messages()->create([
                'role' => 'assistant',
                'content' => 'Sorry, I\'m having trouble connecting right now. Please try again in a moment.',
                'tokens' => 0,
            ]);
        }
    }

    /**
     * Prepare message history for OpenAI API
     */
    protected function prepareMessages(Chatbot $chatbot, Conversation $conversation, string $currentMessage = null, $imageFile = null, string $language = 'en'): array
    {
        $messages = [];

        // Get the last user message to use for RAG context retrieval
        $lastUserMessage = $currentMessage ?? $conversation->messages()
            ->where('role', 'user')
            ->latest()
            ->first()?->content;

        // Retrieve relevant context from website
        $context = '';
        if ($lastUserMessage) {
            $context = $this->ragService->retrieveContext($chatbot, $lastUserMessage);
        }

        // Build system prompt with context
        $systemPrompt = '';

        if ($chatbot->system_prompt) {
            $systemPrompt = $chatbot->system_prompt;
        } else {
            // Default system prompt
            $systemPrompt = 'You are a helpful AI assistant embedded on ' . ($chatbot->name ?? 'a website') . '. Be concise and friendly.';
        }

        // Inject website context if available
        if (!empty($context)) {
            $systemPrompt .= "\n\n" . $context . "\n\nUse the provided information and instructions above to provide accurate, helpful, and specific answers.";
        }

        // Add image analysis instruction if image is provided
        if ($imageFile) {
            $systemPrompt .= "\n\nThe user has uploaded an image. Analyze it carefully and provide a detailed, helpful response about what you see in the image.";
        }

        // Add language instruction
        $langNames = [
            'en' => 'English',
            'hi' => 'Hindi',
            'bn' => 'Bengali',
            'te' => 'Telugu',
            'mr' => 'Marathi',
            'ta' => 'Tamil',
            'gu' => 'Gujarati',
            'kn' => 'Kannada',
            'ml' => 'Malayalam',
            'pa' => 'Punjabi',
            'es' => 'Spanish',
            'fr' => 'French',
            'de' => 'German',
            'it' => 'Italian',
            'pt' => 'Portuguese',
            'ru' => 'Russian',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'zh' => 'Chinese',
            'ar' => 'Arabic',
            'tr' => 'Turkish',
            'vi' => 'Vietnamese',
            'th' => 'Thai',
            'id' => 'Indonesian',
        ];
        $langName = $langNames[$language] ?? 'English';
        $scriptNote = match ($language) {
            'hi', 'mr' => ' (Devanagari script)',
            'bn' => ' (Bengali script)',
            'pa' => ' (Gurmukhi script)',
            'gu' => ' (Gujarati script)',
            'ta' => ' (Tamil script)',
            'te' => ' (Telugu script)',
            'kn' => ' (Kannada script)',
            'ml' => ' (Malayalam script)',
            'ar' => ' (Arabic script)',
            'zh' => ' (Chinese characters)',
            'ja' => ' (Japanese script)',
            'ko' => ' (Korean Hangul)',
            'ru' => ' (Cyrillic script)',
            default => '',
        };
        $systemPrompt .= "\n\nCRITICAL: You MUST respond in {$langName}{$scriptNote}. Even if the user message is in another language, your reply MUST be ONLY in {$langName}. Do not provide translations unless asked.";

        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt,
        ];

        // Add conversation history (last 10 messages for context)
        $history = $conversation->messages()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->reverse();

        foreach ($history as $msg) {
            // Skip the last user message if we have an image (we'll add it with the image)
            if ($imageFile && $msg->role === 'user' && $msg->content === $currentMessage) {
                continue;
            }

            $messages[] = [
                'role' => $msg->role,
                'content' => $msg->content,
            ];
        }

        // Add current message with image if provided
        if ($imageFile && $currentMessage) {
            // Convert image to base64
            $imageData = base64_encode(file_get_contents($imageFile->getRealPath()));
            $mimeType = $imageFile->getMimeType();

            $messages[] = [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $currentMessage,
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => "data:{$mimeType};base64,{$imageData}",
                        ],
                    ],
                ],
            ];
        }

        return $messages;
    }

    /**
     * Transcribe audio file using OpenAI Whisper
     */
    public function transcribeAudio($audioFile): ?string
    {
        try {
            // OpenAI Whisper requires a valid file extension to detect format
            // Livewire temp files often lack extensions, so we create a copy with .webm
            $tmpPath = sys_get_temp_dir() . '/' . uniqid('voice_') . '.webm';
            copy($audioFile->getRealPath(), $tmpPath);

            $response = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen($tmpPath, 'r'),
            ]);

            // Clean up temp file
            @unlink($tmpPath);

            return $response->text;
        } catch (\Exception $e) {
            Log::error('Whisper Transcription Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get or create a conversation for a visitor
     */
    public function getOrCreateConversation(Chatbot $chatbot, string $visitorId): Conversation
    {
        return Conversation::firstOrCreate(
            [
                'chatbot_id' => $chatbot->id,
                'visitor_id' => $visitorId,
            ]
        );
    }
}
