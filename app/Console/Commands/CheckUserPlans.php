<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Subscription;
use Illuminate\Console\Command;

class CheckUserPlans extends Command
{
    protected $signature = 'user:check-plans {email?}';
    protected $description = 'Check user plans and subscriptions';

    public function handle()
    {
        $email = $this->argument('email');

        if ($email) {
            $users = User::where('email', $email)->get();
        } else {
            $users = User::with('plan')->get();
        }

        if ($users->isEmpty()) {
            $this->error('No users found');
            return 1;
        }

        $this->info('=== User Plans Report ===');
        $this->newLine();

        foreach ($users as $user) {
            $this->info("User: {$user->name} ({$user->email})");
            $this->info("  ID: {$user->id}");
            $this->info("  Plan ID: " . ($user->plan_id ?? 'NULL'));
            $this->info("  Plan Name: " . ($user->plan->name ?? 'No Plan'));
            $this->info("  Currency: {$user->currency}");
            $this->info("  Messages This Month: {$user->messages_this_month}");

            // Check subscriptions
            $subscriptions = Subscription::where('user_id', $user->id)->get();
            $this->info("  Subscriptions: " . $subscriptions->count());

            foreach ($subscriptions as $sub) {
                $this->info("    - ID: {$sub->id}, Plan: {$sub->plan->name}, Status: {$sub->status}, Gateway: {$sub->payment_gateway}");
            }

            $this->newLine();
        }

        return 0;
    }
}
