<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class CleanupDuplicateSubscriptions extends Command
{
    protected $signature = 'subscriptions:cleanup';
    protected $description = 'Remove duplicate subscriptions, keeping only the latest active one per user';

    public function handle()
    {
        $this->info('Cleaning up duplicate subscriptions...');

        // Get all users with multiple active subscriptions
        $duplicates = Subscription::selectRaw('user_id, COUNT(*) as count')
            ->where('status', 'active')
            ->groupBy('user_id')
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('No duplicate subscriptions found.');
            return 0;
        }

        $this->info("Found {$duplicates->count()} users with duplicate subscriptions.");

        foreach ($duplicates as $duplicate) {
            $userId = $duplicate->user_id;

            // Get all active subscriptions for this user
            $subscriptions = Subscription::where('user_id', $userId)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc')
                ->get();

            // Keep the latest one, mark others as cancelled
            $latest = $subscriptions->first();
            $this->info("User ID {$userId}: Keeping subscription #{$latest->id}, cancelling " . ($subscriptions->count() - 1) . " others");

            foreach ($subscriptions->skip(1) as $old) {
                $old->update([
                    'status' => 'cancelled',
                    'cancelled_at' => now()
                ]);
                $this->info("  - Cancelled subscription #{$old->id}");
            }
        }

        $this->info('✓ Cleanup complete!');
        return 0;
    }
}
