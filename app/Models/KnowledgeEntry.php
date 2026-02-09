<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeEntry extends Model
{
    protected $fillable = [
        'chatbot_id',
        'website_page_id',
        'content',
        'source_url',
        'category',
        'usage_count',
    ];

    protected $casts = [
        'usage_count' => 'integer',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function websitePage(): BelongsTo
    {
        return $this->belongsTo(WebsitePage::class);
    }

    /**
     * Increment usage count when this entry is used
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }
}
