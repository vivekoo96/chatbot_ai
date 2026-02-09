<?php

use App\Models\User;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Support\Facades\Notification;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// We need a user to send to. I'll pick the first one or create a dummy.
$user = User::first();

if (!$user) {
    echo "No user found to send test email to.\n";
    exit(1);
}

echo "Sending test verification email to: " . $user->email . "\n";
echo "Using Mail Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Using Mail From: " . config('mail.from.address') . "\n";

try {
    $user->notify(new CustomVerifyEmail('https://example.com/verify'));
    echo "SUCCESS: Test email sent successfully!\n";
} catch (\Exception $e) {
    echo "FAILURE: Failed to send test email.\n";
    echo "Error: " . $e->getMessage() . "\n";
}
