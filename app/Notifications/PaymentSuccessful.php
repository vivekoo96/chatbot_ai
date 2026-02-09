<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessful extends Notification
{
    use Queueable;

    public $record;
    public $amount;
    public $type;

    /**
     * Create a new notification instance.
     */
    public function __construct($record, $amount, $type = 'subscription')
    {
        $this->record = $record;
        $this->amount = $amount;
        $this->type = $type;
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
        $invoiceService = app(\App\Services\InvoiceService::class);
        $invoiceData = $invoiceService->getInvoiceData($this->type, $this->record);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('billing.invoice-pdf', $invoiceData);
        $pdfContent = $pdf->output();

        $invoiceNo = $invoiceData['payment']['invoice_no'];
        $siteName = $invoiceData['settings']['site_name'];

        return (new MailMessage)
            ->subject('Payment Successful - ' . $siteName)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We have successfully processed your payment of ' . number_format($this->amount, 2) . ' INR.')
            ->line('Thank you for choosing ' . $siteName . '. Your invoice is attached to this email.')
            ->action('Go to Dashboard', url('/dashboard'))
            ->attachData($pdfContent, $invoiceNo . '.pdf', [
                'mime' => 'application/pdf',
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
