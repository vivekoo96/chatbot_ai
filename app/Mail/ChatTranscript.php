<?php

namespace App\Mail;

use App\Models\Chatbot;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue; // Imported ShouldQueue

class ChatTranscript extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $messages;
    public $chatbot;

    /**
     * Create a new message instance.
     */
    public function __construct($messages, Chatbot $chatbot)
    {
        $this->messages = $messages;
        $this->chatbot = $chatbot;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Chat Transcript from ' . $this->chatbot->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        \Log::info("📨 Processing ChatTranscript email for Chatbot: {$this->chatbot->name}");

        return new Content(
            view: 'emails.transcript',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
