<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\UserAddon;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceService
{
    /**
     * Get invoice data for a given record (Subscription or UserAddon)
     */
    public function getInvoiceData($type, $record)
    {
        $user = $record->user;
        $paymentInfo = [];

        if ($type === 'subscription' || $record instanceof Subscription) {
            $record = ($record instanceof Subscription) ? $record : Subscription::findOrFail($record);
            $subtotal = (float) ($record->plan->price_inr ?? 0);
            $gstPercent = (float) Setting::getValue('gst_percent', 0);
            $gstAmount = ($subtotal * $gstPercent) / 100;

            $paymentInfo = [
                'type' => 'subscription',
                'invoice_no' => 'SUB-' . str_pad($record->id, 6, '0', STR_PAD_LEFT),
                'date' => $record->created_at,
                'description' => ($record->plan->name ?? 'N/A') . ' Plan Subscription',
                'subtotal' => $subtotal,
                'gst_percent' => $gstPercent,
                'gst_amount' => $gstAmount,
                'total' => $subtotal + $gstAmount,
                'status' => $record->status,
                'transaction_id' => $record->razorpay_subscription_id ?? 'N/A',
            ];
        } else {
            $record = ($record instanceof UserAddon) ? $record : UserAddon::findOrFail($record);
            $total = (float) $record->amount_paid;
            $gstPercent = (float) Setting::getValue('gst_percent', 0);
            $subtotal = $total / (1 + ($gstPercent / 100)); // Reverse calculate if amount_paid includes GST
            $gstAmount = $total - $subtotal;

            $paymentInfo = [
                'type' => 'addon',
                'invoice_no' => 'ADD-' . str_pad($record->id, 6, '0', STR_PAD_LEFT),
                'date' => $record->created_at,
                'description' => ($record->addon->name ?? 'N/A') . ' Add-on',
                'subtotal' => $subtotal,
                'gst_percent' => $gstPercent,
                'gst_amount' => $gstAmount,
                'total' => $total,
                'status' => $record->status,
                'transaction_id' => $record->razorpay_payment_id ?? 'N/A',
            ];
        }

        $settings = [
            'site_name' => Setting::where('key', 'site_name')->first()?->value ?? config('app.name'),
            'support_email' => Setting::where('key', 'support_email')->first()?->value ?? 'support@' . parse_url(config('app.url'), PHP_URL_HOST),
        ];

        return [
            'user' => $user,
            'payment' => $paymentInfo,
            'settings' => $settings,
        ];
    }

    /**
     * Generate PDF for a given record
     */
    public function generatePdf($type, $record)
    {
        $data = $this->getInvoiceData($type, $record);

        return Pdf::loadView('billing.invoice-pdf', $data);
    }
}
