<?php

namespace App\Http\Controllers;

use App\Models\Chatbot;
use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WidgetController extends Controller
{
    protected ChatbotService $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Serve the embeddable JavaScript
     */
    public function embedScript(Request $request)
    {
        $chatbot = null;
        if ($request->has('token')) {
            $chatbot = Chatbot::where('token', $request->token)->first();
        }

        $script = view('widget.embed-script', [
            'chatbot' => $chatbot
        ])->render();

        return response($script)
            ->header('Content-Type', 'application/javascript')
            ->header('Access-Control-Allow-Origin', '*');
    }

    /**
     * Render the chat interface inside iframe
     */
    public function iframe(Request $request, string $token)
    {
        $chatbot = Chatbot::where('token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        // Auto-detect website URL from referer
        $detectedUrl = $request->header('referer');

        // Trigger automatic crawling if needed
        if ($detectedUrl && $chatbot->auto_crawl_enabled) {
            $this->triggerAutoCrawl($chatbot, $detectedUrl);
        }

        return view('widget.iframe', [
            'chatbot' => $chatbot,
        ]);
    }

    /**
     * Trigger automatic website crawling
     */
    protected function triggerAutoCrawl(Chatbot $chatbot, string $url): void
    {
        // Only crawl if not already crawled or pending
        if ($chatbot->crawl_status === 'pending' || !$chatbot->last_crawled_at) {
            $chatbot->update([
                'detected_website_url' => $url,
                'crawl_status' => 'pending'
            ]);

            // Dispatch background job
            \App\Jobs\CrawlWebsiteJob::dispatch($chatbot->id, $url);
        }
    }

    /**
     * Handle chat messages from the widget (Public API)
     */
    public function chat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|uuid',
            'visitor_id' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid request',
                'details' => $validator->errors(),
            ], 422);
        }

        // Find chatbot
        $chatbot = Chatbot::where('token', $request->token)
            ->where('is_active', true)
            ->first();

        if (!$chatbot) {
            return response()->json([
                'error' => 'Bot not found or inactive',
            ], 404);
        }

        // Get or create conversation
        $conversation = $this->chatbotService->getOrCreateConversation(
            $chatbot,
            $request->visitor_id
        );

        // Store detected URL for analytics
        if ($request->header('referer')) {
            $conversation->update(['detected_url' => $request->header('referer')]);
        }

        // Process message
        $responseMessage = $this->chatbotService->sendMessage(
            $chatbot,
            $conversation,
            $request->message
        );

        return response()->json([
            'success' => true,
            'message' => $responseMessage->content,
            'timestamp' => $responseMessage->created_at->toISOString(),
        ]);
    }
}

