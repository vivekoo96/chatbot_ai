<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Console\Command;

class TestSubscriptionSystem extends Command
{
    protected $signature = 'subscription:test';
    protected $description = 'Test subscription system configuration';

    public function handle()
    {
        $this->info('Testing Subscription System...');
        $this->newLine();

        // Test 1: Check database tables
        $this->info('1. Checking database tables...');
        $tables = ['subscriptions', 'plans', 'webhook_events', 'payment_reminders'];
        foreach ($tables as $table) {
            try {
                \DB::table($table)->count();
                $this->info("   ✓ Table '{$table}' exists");
            } catch (\Exception $e) {
                $this->error("   ✗ Table '{$table}' missing");
            }
        }

        // Test 2: Check subscription fields
        $this->info('2. Checking subscription table fields...');
        $fields = [
            'razorpay_subscription_id',
            'razorpay_plan_id',
            'razorpay_customer_id',
            'next_billing_at',
            'failed_payment_count',
            'grace_period_ends_at',
            'auto_renew',
            'cancel_at_period_end'
        ];

        try {
            $subscription = Subscription::first();
            foreach ($fields as $field) {
                if (array_key_exists($field, $subscription?->getAttributes() ?? [])) {
                    $this->info("   ✓ Field '{$field}' exists");
                } else {
                    $this->warn("   ⚠ Field '{$field}' not found (may be null)");
                }
            }
        } catch (\Exception $e) {
            $this->error("   ✗ Error checking fields: " . $e->getMessage());
        }

        // Test 3: Check plans
        $this->info('3. Checking plans...');
        $plans = Plan::orderBy('sort_order')->get(['name', 'price_inr', 'has_api_access']);
        $this->table(
            ['Plan', 'Price (INR)', 'API Access'],
            $plans->map(fn($p) => [$p->name, '₹' . $p->price_inr, $p->has_api_access ? 'Yes' : 'No'])
        );

        // Test 4: Check Razorpay configuration
        $this->info('4. Checking Razorpay configuration...');
        $config = [
            'RAZORPAY_KEY' => config('services.razorpay.key'),
            'RAZORPAY_SECRET' => config('services.razorpay.secret'),
            'RAZORPAY_WEBHOOK_SECRET' => config('services.razorpay.webhook_secret'),
            'RAZORPAY_TEST_MODE' => config('services.razorpay.test_mode'),
        ];

        foreach ($config as $key => $value) {
            if (!empty($value)) {
                $masked = $key === 'RAZORPAY_SECRET' || $key === 'RAZORPAY_WEBHOOK_SECRET'
                    ? str_repeat('*', strlen($value))
                    : $value;
                $this->info("   ✓ {$key}: {$masked}");
            } else {
                $this->warn("   ⚠ {$key}: Not configured");
            }
        }

        // Test 5: Check subscription config
        $this->info('5. Checking subscription configuration...');
        $subConfig = [
            'max_failed_payments' => config('subscription.max_failed_payments'),
            'grace_period_days' => config('subscription.grace_period_days'),
            'payment_reminder_days' => config('subscription.payment_reminder_days'),
        ];

        foreach ($subConfig as $key => $value) {
            $this->info("   ✓ {$key}: {$value}");
        }

        // Test 6: Check queue configuration
        $this->info('6. Checking queue configuration...');
        $queueDriver = config('queue.default');
        $this->info("   Queue Driver: {$queueDriver}");

        if ($queueDriver === 'sync') {
            $this->warn("   ⚠ Queue is set to 'sync' - webhooks will process synchronously");
            $this->warn("   ⚠ For production, use 'database' or 'redis'");
        } else {
            $this->info("   ✓ Queue configured for async processing");
        }

        // Test 7: Check routes
        $this->info('7. Checking routes...');
        $routes = [
            'webhooks.razorpay' => 'POST /webhooks/razorpay',
            'checkout' => 'GET /checkout/{plan}',
            'subscription.cancel' => 'POST /subscription/cancel',
            'subscription.resume' => 'POST /subscription/resume',
        ];

        foreach ($routes as $name => $path) {
            if (\Route::has($name)) {
                $this->info("   ✓ Route '{$name}' exists: {$path}");
            } else {
                $this->error("   ✗ Route '{$name}' missing");
            }
        }

        $this->newLine();
        $this->info('✓ System check complete!');
        $this->newLine();

        // Show next steps
        $this->warn('Next Steps:');
        $this->line('1. Configure Razorpay webhook URL in dashboard');
        $this->line('2. Create subscription plans in Razorpay dashboard');
        $this->line('3. Enable UPI AutoPay in Razorpay settings');
        $this->line('4. Set up queue worker (supervisor or systemd)');
        $this->line('5. Test webhook with: php artisan webhook:test-logging');

        return 0;
    }
}
