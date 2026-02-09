<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Chatbot extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'whatsapp_number',
        'token',
        'system_prompt',
        'theme_color',
        'allowed_domains',
        'is_active',
        'detected_website_url',
        'last_crawled_at',
        'pages_crawled',
        'auto_crawl_enabled',
        'crawl_status',
    ];

    protected $casts = [
        'allowed_domains' => 'array',
        'is_active' => 'boolean',
        'auto_crawl_enabled' => 'boolean',
        'last_crawled_at' => 'datetime',
        'pages_crawled' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Chatbot $chatbot) {
            if (empty($chatbot->token)) {
                $chatbot->token = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function websitePages(): HasMany
    {
        return $this->hasMany(WebsitePage::class);
    }

    public function knowledgeEntries(): HasMany
    {
        return $this->hasMany(KnowledgeEntry::class);
    }

    public function conversationInsights(): HasMany
    {
        return $this->hasMany(ConversationInsight::class);
    }
}
