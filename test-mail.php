<?php

use Illuminate\Support\Facades\Mail;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Attempting to send test email to vvivek814@gmail.com...\n";
    Mail::raw('This is a test email sent from HemnixBot SMTP test script.', function ($message) {
        $message->to('vvivek814@gmail.com')
            ->subject('SMTP Verification Test');
    });
    echo "Success! Email sent successfully.\n";
} catch (\Exception $e) {
    echo "ERROR: Failed to send email.\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
