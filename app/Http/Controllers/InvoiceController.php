<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\UserAddon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    protected $invoiceService;

    public function __construct(\App\Services\InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function download($type, $id)
    {
        $user = Auth::user();

        if ($type === 'subscription') {
            $record = Subscription::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        } else {
            $record = UserAddon::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        }

        $data = $this->invoiceService->getInvoiceData($type, $record);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('billing.invoice-pdf', $data);

        return $pdf->download($data['payment']['invoice_no'] . '.pdf');
    }
}
