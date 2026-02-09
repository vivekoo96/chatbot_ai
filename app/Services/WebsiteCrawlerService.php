<?php

namespace App\Services;

use App\Models\Chatbot;
use App\Models\WebsitePage;
use App\Models\KnowledgeEntry;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class WebsiteCrawlerService
{
    protected int $maxPages = 100;
    protected int $chunkSize = 800; // Characters per chunk
    protected array $visitedUrls = [];

    /**
     * Crawl a website and store content
     */
    public function crawlWebsite(Chatbot $chatbot, string $detectedUrl): void
    {
        try {
            // Update status to crawling
            $chatbot->update(['crawl_status' => 'crawling']);

            // Extract base URL
            $baseUrl = $this->getBaseUrl($detectedUrl);

            // Start crawling from the base URL
            $this->crawlPage($chatbot, $baseUrl, $baseUrl);

            // Update chatbot with crawl results
            $chatbot->update([
                'crawl_status' => 'completed',
                'last_crawled_at' => now(),
                'pages_crawled' => count($this->visitedUrls),
            ]);

            Log::info("Website crawl completed for chatbot {$chatbot->id}: {$chatbot->pages_crawled} pages");

        } catch (\Exception $e) {
            $chatbot->update(['crawl_status' => 'failed']);
            Log::error("Website crawl failed for chatbot {$chatbot->id}: " . $e->getMessage());
        }
    }

    /**
     * Crawl a single page
     */
    protected function crawlPage(Chatbot $chatbot, string $url, string $baseUrl): void
    {
        // Check limits
        if (count($this->visitedUrls) >= $this->maxPages) {
            return;
        }

        // Skip if already visited
        if (in_array($url, $this->visitedUrls)) {
            return;
        }

        // Mark as visited
        $this->visitedUrls[] = $url;

        try {
            // Fetch page content
            $response = Http::timeout(10)->get($url);

            if (!$response->successful()) {
                return;
            }

            $html = $response->body();
            $crawler = new Crawler($html, $url);

            // Extract content
            $pageData = $this->extractContent($crawler, $url);

            // Store page
            $websitePage = WebsitePage::updateOrCreate(
                ['url' => $url],
                [
                    'chatbot_id' => $chatbot->id,
                    'title' => $pageData['title'],
                    'content' => $pageData['content'],
                    'meta_description' => $pageData['meta_description'],
                    'headings' => $pageData['headings'],
                    'last_crawled_at' => now(),
                ]
            );

            // Create knowledge entries (chunks)
            $this->createKnowledgeEntries($chatbot, $websitePage, $pageData);

            // Find and crawl linked pages (limit to same domain)
            $links = $this->extractLinks($crawler, $baseUrl);
            foreach ($links as $link) {
                if (count($this->visitedUrls) < $this->maxPages) {
                    $this->crawlPage($chatbot, $link, $baseUrl);
                }
            }

        } catch (\Exception $e) {
            Log::warning("Failed to crawl page {$url}: " . $e->getMessage());
        }
    }

    /**
     * Extract content from a page
     */
    protected function extractContent(Crawler $crawler, string $url): array
    {
        // Extract title
        $title = $crawler->filter('title')->count() > 0
            ? $crawler->filter('title')->text()
            : '';

        // Extract meta description
        $metaDescription = $crawler->filter('meta[name="description"]')->count() > 0
            ? $crawler->filter('meta[name="description"]')->attr('content')
            : '';

        // Extract headings
        $headings = [];
        foreach (['h1', 'h2', 'h3'] as $tag) {
            $crawler->filter($tag)->each(function (Crawler $node) use (&$headings, $tag) {
                $headings[$tag][] = trim($node->text());
            });
        }

        // Extract main content (paragraphs, lists, etc.)
        $content = '';
        $crawler->filter('p, li, td, div.content, article')->each(function (Crawler $node) use (&$content) {
            $text = trim($node->text());
            if (strlen($text) > 20) { // Skip very short text
                $content .= $text . "\n";
            }
        });

        // Clean up content
        $content = $this->cleanContent($content);

        return [
            'title' => $title,
            'meta_description' => $metaDescription,
            'headings' => $headings,
            'content' => $content,
        ];
    }

    /**
     * Create knowledge entries from page content
     */
    protected function createKnowledgeEntries(Chatbot $chatbot, WebsitePage $websitePage, array $pageData): void
    {
        // Delete old entries for this page
        KnowledgeEntry::where('website_page_id', $websitePage->id)->delete();

        // Combine all text
        $fullText = $pageData['title'] . "\n\n";

        // Add headings
        foreach ($pageData['headings'] as $level => $texts) {
            $fullText .= implode("\n", $texts) . "\n";
        }

        $fullText .= "\n" . $pageData['content'];

        // Split into chunks
        $chunks = $this->chunkContent($fullText);

        // Determine category
        $category = $this->categorizeContent($pageData['title'], $fullText);

        // Create knowledge entries
        foreach ($chunks as $chunk) {
            KnowledgeEntry::create([
                'chatbot_id' => $chatbot->id,
                'website_page_id' => $websitePage->id,
                'content' => $chunk,
                'source_url' => $websitePage->url,
                'category' => $category,
            ]);
        }
    }

    /**
     * Split content into chunks
     */
    protected function chunkContent(string $content): array
    {
        $chunks = [];
        $words = explode(' ', $content);
        $currentChunk = '';

        foreach ($words as $word) {
            if (strlen($currentChunk . ' ' . $word) > $this->chunkSize) {
                if (!empty(trim($currentChunk))) {
                    $chunks[] = trim($currentChunk);
                }
                $currentChunk = $word;
            } else {
                $currentChunk .= ' ' . $word;
            }
        }

        // Add last chunk
        if (!empty(trim($currentChunk))) {
            $chunks[] = trim($currentChunk);
        }

        return $chunks;
    }

    /**
     * Categorize content based on keywords
     */
    protected function categorizeContent(string $title, string $content): string
    {
        $text = strtolower($title . ' ' . $content);

        if (Str::contains($text, ['product', 'shop', 'buy', 'price', '$', 'cart'])) {
            return 'product';
        }
        if (Str::contains($text, ['service', 'offer', 'solution'])) {
            return 'service';
        }
        if (Str::contains($text, ['pricing', 'plan', 'subscription', 'cost'])) {
            return 'pricing';
        }
        if (Str::contains($text, ['about', 'company', 'team', 'mission'])) {
            return 'about';
        }
        if (Str::contains($text, ['contact', 'email', 'phone', 'address'])) {
            return 'contact';
        }

        return 'general';
    }

    /**
     * Extract links from page
     */
    protected function extractLinks(Crawler $crawler, string $baseUrl): array
    {
        $links = [];
        $baseDomain = parse_url($baseUrl, PHP_URL_HOST);

        $crawler->filter('a')->each(function (Crawler $node) use (&$links, $baseUrl, $baseDomain) {
            $href = $node->attr('href');

            if (empty($href) || $href === '#') {
                return;
            }

            // Convert relative URLs to absolute
            $absoluteUrl = $this->makeAbsoluteUrl($href, $baseUrl);

            // Only include same-domain links
            $linkDomain = parse_url($absoluteUrl, PHP_URL_HOST);
            if ($linkDomain === $baseDomain && !in_array($absoluteUrl, $links)) {
                $links[] = $absoluteUrl;
            }
        });

        return array_slice($links, 0, 20); // Limit links per page
    }

    /**
     * Convert relative URL to absolute
     */
    protected function makeAbsoluteUrl(string $url, string $baseUrl): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        $base = parse_url($baseUrl);
        $scheme = $base['scheme'] ?? 'https';
        $host = $base['host'] ?? '';

        if (str_starts_with($url, '//')) {
            return $scheme . ':' . $url;
        }

        if (str_starts_with($url, '/')) {
            return $scheme . '://' . $host . $url;
        }

        return $scheme . '://' . $host . '/' . ltrim($url, '/');
    }

    /**
     * Get base URL from any URL
     */
    protected function getBaseUrl(string $url): string
    {
        $parsed = parse_url($url);
        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? '';

        return $scheme . '://' . $host;
    }

    /**
     * Clean content text
     */
    protected function cleanContent(string $content): string
    {
        // Remove excessive whitespace
        $content = preg_replace('/\s+/', ' ', $content);

        // Remove special characters but keep basic punctuation
        $content = preg_replace('/[^\w\s\.\,\!\?\-\$\%\@\(\)]/u', '', $content);

        return trim($content);
    }
}
