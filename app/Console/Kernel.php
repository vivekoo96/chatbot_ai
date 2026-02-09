<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ============================================
        // WEBSITE CRAWLING - Keep RAG knowledge up-to-date
        // ============================================
        $schedule->call(function () {
            \Log::info('[CRON] Website crawling job started');
            $chatbots = \App\Models\Chatbot::where('is_active', true)->get();
            foreach ($chatbots as $chatbot) {
                if ($chatbot->detected_website_url) {
                    \Log::info("[CRON] Dispatching CrawlWebsiteJob for chatbot {$chatbot->id} ({$chatbot->detected_website_url})");
                    \App\Jobs\CrawlWebsiteJob::dispatch($chatbot->id, $chatbot->detected_website_url);
                }
            }
            \Log::info('[CRON] Website crawling job finished');
        })->hourly();
        // ============================================
        // QUEUE WORKER - Process jobs every minute
        // ============================================
        $schedule->command('queue:work --stop-when-empty --max-time=60 --tries=3')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground()
            ->appendOutputTo(storage_path('logs/queue.log'));

        // ============================================
        // FAILED JOBS - Retry every hour
        // ============================================
        $schedule->command('queue:retry all')
            ->hourly()
            ->runInBackground();

        // ============================================
        // ADD-ONS - Expire daily at midnight
        // ============================================
        $schedule->call(function () {
            $service = app(\App\Services\AddonService::class);
            $expired = $service->expireAddons();
            Log::info("Add-ons expired: {$expired}");
        })->daily();

        // ============================================
        // SUBSCRIPTIONS - Check grace periods daily
        // ============================================
        $schedule->call(function () {
            $subscriptions = \App\Models\Subscription::where('status', 'active')
                ->whereNotNull('grace_period_ends_at')
                ->where('grace_period_ends_at', '<=', now())
                ->get();

            foreach ($subscriptions as $subscription) {
                if ($subscription->shouldDowngrade()) {
                    $service = app(\App\Services\SubscriptionService::class);
                    $service->handleGracePeriodExpiry($subscription);
                    Log::info("Subscription downgraded after grace period", [
                        'subscription_id' => $subscription->id,
                        'user_id' => $subscription->user_id
                    ]);
                }
            }
        })->daily();

        // ============================================
        // PAYMENT REMINDERS - Send 3 days before billing
        // ============================================
        $schedule->call(function () {
            $reminderDays = config('subscription.payment_reminder_days', 3);
            $upcomingDate = now()->addDays($reminderDays);

            $subscriptions = \App\Models\Subscription::where('status', 'active')
                ->whereDate('next_billing_at', $upcomingDate->toDateString())
                ->with('user')
                ->get();

            foreach ($subscriptions as $subscription) {
                // Check if reminder already sent
                if (
                    !\App\Models\PaymentReminder::wasAlreadySent(
                        $subscription->user_id,
                        $subscription->id,
                        'upcoming_payment'
                    )
                ) {
                    // Send notification
                    $subscription->user->notify(
                        new \App\Notifications\UpcomingPayment($subscription)
                    );

                    // Log reminder
                    \App\Models\PaymentReminder::create([
                        'user_id' => $subscription->user_id,
                        'subscription_id' => $subscription->id,
                        'type' => 'upcoming_payment',
                        'is_sent' => true,
                        'sent_at' => now(),
                    ]);
                }
            }
        })->daily();

        // ============================================
        // QUEUE MONITORING - Log stats every 5 minutes
        // ============================================
        $schedule->call(function () {
            $pending = DB::table('jobs')->count();
            $failed = DB::table('failed_jobs')->count();

            if ($pending > 100) {
                Log::warning("Queue backlog detected", [
                    'pending' => $pending,
                    'failed' => $failed
                ]);
            } else {
                Log::info("Queue stats", [
                    'pending' => $pending,
                    'failed' => $failed
                ]);
            }
        })->everyFiveMinutes();

        // ============================================
        // DATABASE CLEANUP - Clean old records weekly
        // ============================================
        $schedule->call(function () {
            // Clean old webhook events (keep 30 days)
            $deleted = \App\Models\WebhookEvent::where('created_at', '<', now()->subDays(30))->delete();
            Log::info("Cleaned old webhook events: {$deleted}");

            // Clean old payment reminders (keep 90 days)
            $deleted = \App\Models\PaymentReminder::where('created_at', '<', now()->subDays(90))->delete();
            Log::info("Cleaned old payment reminders: {$deleted}");
        })->weekly();

        // ============================================
        // PLAN LIMITS RESET - Reset monthly usage
        // ============================================
        $schedule->call(function () {
            $users = \App\Models\User::whereNotNull('billing_cycle_start')
                ->whereDate('billing_cycle_start', now()->toDateString())
                ->get();

            foreach ($users as $user) {
                $user->update([
                    'messages_this_month' => 0,
                    'billing_cycle_start' => now(),
                ]);
                Log::info("Reset monthly usage for user", ['user_id' => $user->id]);
            }
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
