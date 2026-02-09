<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Config;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Set some test settings
Setting::updateOrCreate(['key' => 'site_name'], ['value' => 'Test Site Name']);
Setting::updateOrCreate(['key' => 'openai_key'], ['value' => 'sk-test-key-123']);

// Re-run AppServiceProvider boot or just check if it's already applied
// Since we are in a new process, the AppServiceProvider boot should have run if the DB was ready.

// Check DB values
$dbSiteName = Setting::where('key', 'site_name')->first()?->value;
$dbOpenAIKey = Setting::where('key', 'openai_key')->first()?->value;

echo "DB Site Name: " . $dbSiteName . "\n";
echo "DB OpenAI Key: " . $dbOpenAIKey . "\n";

echo "App Name: " . config('app.name') . "\n";
echo "OpenAI Key (services): " . config('services.openai.key') . "\n";
echo "OpenAI Key (openai): " . config('openai.api_key') . "\n";

if (config('app.name') === $dbSiteName && config('openai.api_key') === $dbOpenAIKey) {
    echo "SUCCESS: Settings are being overridden correctly.\n";
} else {
    echo "FAILURE: Settings are NOT being overridden correctly.\n";
}

