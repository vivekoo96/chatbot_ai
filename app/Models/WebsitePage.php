<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebsitePage extends Model
{
    protected $fillable = [
        'chatbot_id',
        'url',
        'title',
        'content',
        'meta_description',
        'headings',
        'last_crawled_at',
    ];

    protected $casts = [
        'headings' => 'array',
        'last_crawled_at' => 'datetime',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function knowledgeEntries(): HasMany
    {
        return $this->hasMany(KnowledgeEntry::class);
    }
}
