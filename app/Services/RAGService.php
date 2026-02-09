<?php

namespace App\Services;

use App\Models\Chatbot;
use App\Models\KnowledgeEntry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RAGService
{
    protected int $maxResults = 5;
    protected int $maxContextLength = 1500; // Max characters for context

    /**
     * Retrieve relevant context for a query
     */
    public function retrieveContext(Chatbot $chatbot, string $query): string
    {
        // Search for relevant knowledge entries
        $entries = $this->searchKnowledge($query, $chatbot->id);

        if ($entries->isEmpty()) {
            return '';
        }

        // Track usage for these entries
        foreach ($entries as $entry) {
            $entry->incrementUsage();
        }

        // Format context for AI
        return $this->formatContext($entries);
    }

    /**
     * Search knowledge base using full-text search
     */
    protected function searchKnowledge(string $query, int $chatbotId): Collection
    {
        // Extract keywords from query
        $keywords = $this->extractKeywords($query);

        if (empty($keywords)) {
            return collect();
        }

        // Build search query
        $searchQuery = implode(' ', $keywords);

        // Full-text search with relevance scoring
        $entries = KnowledgeEntry::where('chatbot_id', $chatbotId)
            ->whereRaw("MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE)", [$searchQuery])
            ->orderByRaw("MATCH(content) AGAINST(? IN NATURAL LANGUAGE MODE) DESC", [$searchQuery])
            ->orderBy('usage_count', 'desc') // Prioritize frequently used entries
            ->take($this->maxResults)
            ->get();

        // If no results, try simple LIKE search
        if ($entries->isEmpty()) {
            $entries = $this->fallbackSearch($keywords, $chatbotId);
        }

        return $entries;
    }

    /**
     * Fallback search using LIKE
     */
    protected function fallbackSearch(array $keywords, int $chatbotId): Collection
    {
        $query = KnowledgeEntry::where('chatbot_id', $chatbotId);

        foreach ($keywords as $keyword) {
            $query->where('content', 'LIKE', "%{$keyword}%");
        }

        return $query->orderBy('usage_count', 'desc')
            ->take($this->maxResults)
            ->get();
    }

    /**
     * Extract keywords from query
     */
    protected function extractKeywords(string $query): array
    {
        // Convert to lowercase
        $query = strtolower($query);

        // Remove common stop words
        $stopWords = ['the', 'is', 'at', 'which', 'on', 'a', 'an', 'and', 'or', 'but', 'in', 'with', 'to', 'for', 'of', 'as', 'by', 'do', 'does', 'did', 'what', 'when', 'where', 'who', 'how', 'can', 'could', 'would', 'should', 'i', 'you', 'we', 'they'];

        // Split into words
        $words = preg_split('/\s+/', $query);

        // Filter out stop words and short words
        $keywords = array_filter($words, function ($word) use ($stopWords) {
            return strlen($word) > 2 && !in_array($word, $stopWords);
        });

        return array_values($keywords);
    }

    /**
     * Format context for AI prompt
     */
    protected function formatContext(Collection $entries): string
    {
        $manualEntries = $entries->filter(fn($e) => empty($e->website_page_id));
        $websiteEntries = $entries->filter(fn($e) => !empty($e->website_page_id));

        $context = "";
        $totalLength = 0;

        // Prioritize manual/uploaded entries (including file uploads)
        if ($manualEntries->isNotEmpty()) {
            $context .= "Uploaded & Custom Knowledge Base:\n";
            foreach ($manualEntries as $entry) {
                $entryText = "- " . trim($entry->content) . "\n";
                if ($totalLength + strlen($entryText) > $this->maxContextLength)
                    break;
                $context .= $entryText;
                $totalLength += strlen($entryText);
            }
            $context .= "\n";
        }

        // Add website content only if space remains
        if ($websiteEntries->isNotEmpty() && $totalLength < $this->maxContextLength) {
            $context .= "Website Information (Crawled Content):\n";
            foreach ($websiteEntries as $entry) {
                $entryText = "- " . trim($entry->content) . "\n";
                if ($totalLength + strlen($entryText) > $this->maxContextLength)
                    break;
                $context .= $entryText;
                $totalLength += strlen($entryText);
            }
        }

        return trim($context);
    }

    /**
     * Get insights for common questions
     */
    public function getInsights(Chatbot $chatbot): Collection
    {
        return $chatbot->conversationInsights()
            ->orderBy('frequency', 'desc')
            ->take(10)
            ->get();
    }
}
