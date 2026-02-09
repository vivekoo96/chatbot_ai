<?php

namespace App\Jobs;

use App\Models\Chatbot;
use App\Services\WebsiteCrawlerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CrawlWebsiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600; // 10 minutes
    public int $tries = 1; // Don't retry

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $chatbotId,
        public string $detectedUrl
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(WebsiteCrawlerService $crawlerService): void
    {
        try {
            $chatbot = Chatbot::find($this->chatbotId);

            if (!$chatbot) {
                Log::error("Chatbot not found for crawl job: {$this->chatbotId}");
                return;
            }

            Log::info("Starting website crawl for chatbot {$chatbot->id}: {$this->detectedUrl}");

            // Perform the crawl
            $crawlerService->crawlWebsite($chatbot, $this->detectedUrl);

            Log::info("Completed website crawl for chatbot {$chatbot->id}");

        } catch (\Exception $e) {
            Log::error("Crawl job failed for chatbot {$this->chatbotId}: " . $e->getMessage());

            // Update chatbot status to failed
            if ($chatbot = Chatbot::find($this->chatbotId)) {
                $chatbot->update(['crawl_status' => 'failed']);
            }
        }
    }
}
