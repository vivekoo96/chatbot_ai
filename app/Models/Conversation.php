<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'chatbot_id',
        'visitor_id',
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'detected_url',
        'user_country',
        'user_language',
        'is_manual_mode',
    ];

    public function chatbot(): BelongsTo
    {
        return $this->belongsTo(Chatbot::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
