<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamInvitationNotification extends Notification
{
    use Queueable;

    protected $inviterName;
    protected $businessName;
    protected $email;
    protected $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($inviterName, $businessName, $email, $password)
    {
        $this->inviterName = $inviterName;
        $this->businessName = $businessName;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invitation to join ' . $this->businessName)
            ->view('emails.team-invitation', [
                'inviterName' => $this->inviterName,
                'businessName' => $this->businessName,
                'email' => $this->email,
                'password' => $this->password
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
