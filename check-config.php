<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Current Mail Config:\n";
echo "Mailer: " . config('mail.default') . "\n";
echo "From Address: " . config('mail.from.address') . "\n";
echo "From Name: " . config('mail.from.name') . "\n";

$driver = config('mail.default');
echo "\nSMTP Config:\n";
echo "Host: " . config("mail.mailers.$driver.host") . "\n";
echo "Port: " . config("mail.mailers.$driver.port") . "\n";
echo "Encryption: " . config("mail.mailers.$driver.encryption") . "\n";
echo "Username: " . config("mail.mailers.$driver.username") . "\n";
echo "Password: " . (config("mail.mailers.$driver.password") ? '********' : 'NULL') . "\n";

echo "\nDatabase Settings Override:\n";
try {
    if (class_exists(\App\Models\Setting::class)) {
        foreach (\App\Models\Setting::all() as $s) {
            echo $s->key . ': ' . $s->value . "\n";
        }
    }
} catch (\Exception $e) {
    echo "Error reading settings table: " . $e->getMessage() . "\n";
}
