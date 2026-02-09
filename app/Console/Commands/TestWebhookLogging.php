<?php

namespace App\Console\Commands;

use App\Models\WebhookEvent;
use Illuminate\Console\Command;

class TestWebhookLogging extends Command
{
    protected $signature = 'webhook:test-logging';
    protected $description = 'Test webhook event logging system';

    public function handle()
    {
        $this->info('Testing Webhook Logging System...');
        $this->newLine();

        // Test 1: Check webhook_events table
        $this->info('1. Checking webhook_events table...');
        try {
            $count = WebhookEvent::count();
            $this->info("   ✓ Table exists. Total events: {$count}");
        } catch (\Exception $e) {
            $this->error("   ✗ Error: " . $e->getMessage());
            return 1;
        }

        // Test 2: Create test webhook event
        $this->info('2. Creating test webhook event...');
        try {
            $event = WebhookEvent::create([
                'event_id' => 'test_' . uniqid(),
                'event_type' => 'test.event',
                'entity_type' => 'test',
                'entity_id' => 'test_123',
                'payload' => ['test' => 'data'],
                'status' => 'pending',
            ]);
            $this->info("   ✓ Test event created with ID: {$event->id}");
        } catch (\Exception $e) {
            $this->error("   ✗ Error: " . $e->getMessage());
            return 1;
        }

        // Test 3: Update event status
        $this->info('3. Testing status updates...');
        try {
            $event->markAsProcessing();
            $this->info("   ✓ Marked as processing");

            $event->markAsProcessed();
            $this->info("   ✓ Marked as processed");
        } catch (\Exception $e) {
            $this->error("   ✗ Error: " . $e->getMessage());
            return 1;
        }

        // Test 4: Show recent events
        $this->info('4. Recent webhook events:');
        $recent = WebhookEvent::latest()->take(5)->get(['id', 'event_type', 'status', 'created_at']);
        $this->table(
            ['ID', 'Event Type', 'Status', 'Created At'],
            $recent->map(fn($e) => [$e->id, $e->event_type, $e->status, $e->created_at])
        );

        $this->newLine();
        $this->info('✓ All tests passed!');

        return 0;
    }
}
