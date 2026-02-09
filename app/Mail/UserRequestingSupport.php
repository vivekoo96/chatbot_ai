<?php

namespace App\Mail;

use App\Models\Conversation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRequestingSupport extends Mailable
{
    use Queueable, SerializesModels;

    public $conversation;

    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Action Required: User Requesting Support',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.request-support',
        );
    }
}
