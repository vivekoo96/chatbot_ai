<?php

namespace App\Services;

use App\Models\Chatbot;
use App\Models\Conversation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppNotificationService
{
    protected string $accessToken;
    protected string $phoneNumberId;
    protected string $version;

    public function __construct()
    {
        $this->accessToken = config('services.whatsapp.access_token') ?? '';
        $this->phoneNumberId = config('services.whatsapp.phone_number_id') ?? '';
        $this->version = config('services.whatsapp.version', 'v21.0');
    }

    /**
     * Send a notification to the admin when a new conversation starts
     */
    public function notifyAdminOfNewConversation(Chatbot $chatbot, Conversation $conversation, string $firstMessage): bool
    {
        $adminPhone = $chatbot->whatsapp_number;

        if (empty($adminPhone) || empty($this->accessToken) || empty($this->phoneNumberId)) {
            Log::warning('WhatsApp Notification skipped: Missing configuration or admin phone number.', [
                'chatbot_id' => $chatbot->id,
                'has_token' => !empty($this->accessToken),
                'has_phone_id' => !empty($this->phoneNumberId),
                'admin_phone' => $adminPhone
            ]);
            return false;
        }

        // Clean phone number (remove +, spaces, etc.)
        $adminPhone = preg_replace('/[^0-9]/', '', $adminPhone);

        $message = "🚀 *New Conversation Started!*\n\n";
        $message .= "*Assistant:* {$chatbot->name}\n";
        $message .= "*Visitor ID:* " . substr($conversation->visitor_id, 0, 8) . "...\n";
        if ($conversation->visitor_name) {
            $message .= "*Visitor Name:* {$conversation->visitor_name}\n";
        }
        $message .= "\n*First Message:*\n_{$firstMessage}_";

        return $this->sendMessage($adminPhone, $message);
    }

    /**
     * Send a text message via WhatsApp Business API
     */
    public function sendMessage(string $to, string $text): bool
    {
        try {
            $url = "https://graph.facebook.com/{$this->version}/{$this->phoneNumberId}/messages";

            $response = Http::withToken($this->accessToken)->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'preview_url' => false,
                    'body' => $text,
                ],
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', ['to' => $to]);
                return true;
            }

            Log::error('WhatsApp API Error', [
                'status' => $response->status(),
                'response' => $response->json(),
                'to' => $to
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('WhatsApp Service Exception: ' . $e->getMessage());
            return false;
        }
    }
}
