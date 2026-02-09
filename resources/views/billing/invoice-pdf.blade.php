<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice - {{ $payment['invoice_no'] }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
        }

        .header {
            margin-bottom: 40px;
            border-bottom: 2px solid #6366f1;
            padding-bottom: 20px;
        }

        .header table {
            width: 100%;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #6366f1;
        }

        .invoice-title {
            text-align: right;
            font-size: 28px;
            font-weight: bold;
            color: #111827;
        }

        .section {
            margin-bottom: 30px;
        }

        .section table {
            width: 100%;
        }

        .label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .value {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .items-table th {
            text-align: left;
            background-color: #f9fafb;
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
            color: #4b5563;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .total-section {
            margin-top: 30px;
            text-align: right;
        }

        .total-box {
            display: inline-block;
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            min-width: 200px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="logo">{{ $settings['site_name'] }}</div>
                    <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">{{ $settings['support_email'] }}
                    </div>
                </td>
                <td class="invoice-title">INVOICE</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%;">
                    <div class="label">Billed To</div>
                    <div class="value">{{ $user->name }}</div>
                    <div style="font-size: 14px; color: #4b5563;">{{ $user->email }}</div>
                    @if($user->business_name)
                        <div style="font-size: 14px; color: #4b5563;">{{ $user->business_name }}</div>
                    @endif
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="label">Invoice Number</div>
                    <div class="value">{{ $payment['invoice_no'] }}</div>
                    <div class="label" style="margin-top: 10px;">Date</div>
                    <div class="value">{{ $payment['date']->format('F d, Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $payment['description'] }}</td>
                <td style="text-align: right;">₹{{ number_format($payment['subtotal'], 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-box">
            <table style="width: 100%;">
                <tr>
                    <td style="font-size: 14px; color: #4b5563;">Status:</td>
                    <td
                        style="text-align: right; font-weight: bold; color: {{ $payment['status'] === 'active' ? '#059669' : '#374151' }};">
                        {{ strtoupper($payment['status']) }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 14px; color: #4b5563; padding-top: 5px;">Subtotal:</td>
                    <td style="text-align: right; font-size: 14px; color: #4b5563; padding-top: 5px;">
                        ₹{{ number_format($payment['subtotal'], 2) }}
                    </td>
                </tr>
                @if($payment['gst_amount'] > 0)
                    <tr>
                        <td style="font-size: 14px; color: #4b5563; padding-top: 5px;">GST ({{ $payment['gst_percent'] }}%):
                        </td>
                        <td style="text-align: right; font-size: 14px; color: #4b5563; padding-top: 5px;">
                            ₹{{ number_format($payment['gst_amount'], 2) }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td
                        style="font-size: 18px; font-weight: bold; color: #111827; padding-top: 10px; border-top: 1px solid #e5e7eb;">
                        Total:</td>
                    <td
                        style="text-align: right; font-size: 18px; font-weight: bold; color: #6366f1; padding-top: 10px; border-top: 1px solid #e5e7eb;">
                        ₹{{ number_format($payment['total'], 2) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section" style="margin-top: 40px;">
        <div class="label">Transaction ID</div>
        <div style="font-size: 12px; color: #4b5563;">{{ $payment['transaction_id'] }}</div>
    </div>

    <div class="footer">
        Thank you for your business! If you have any questions, please contact us at {{ $settings['support_email'] }}.
    </div>
</body>

</html>