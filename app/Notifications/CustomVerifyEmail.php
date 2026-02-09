<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $otp = (string) rand(100000, 999999);

        $notifiable->update([
            'verification_otp' => $otp,
            'verification_otp_expires_at' => now()->addHour(),
        ]);

        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->view('emails.verify-email', ['otp' => $otp, 'user' => $notifiable]);
    }
}
