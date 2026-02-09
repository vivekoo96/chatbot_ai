<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConversationInsight extends Model
{
    protected $fillable = [
        'chatbot_id',
        'question_pattern',
        'best_answer',
        'frequency',
    ];

    protected $casts = [
        'frequency' => 'integer',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    /**
     * Increment frequency when this pattern is encountered again
     */
    public function incrementFrequency(): void
    {
        $this->increment('frequency');
    }
}
