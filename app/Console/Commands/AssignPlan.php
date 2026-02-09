<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Console\Command;

class AssignPlan extends Command
{
    protected $signature = 'user:assign-plan {email} {plan_slug}';
    protected $description = 'Manually assign a plan to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $planSlug = $this->argument('plan_slug');

        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("User not found: {$email}");
            return 1;
        }

        $plan = Plan::where('slug', $planSlug)->first();
        if (!$plan) {
            $this->error("Plan not found: {$planSlug}");
            $this->info("Available plans: " . Plan::pluck('slug')->implode(', '));
            return 1;
        }

        $this->info("Assigning {$plan->name} to {$user->name}...");

        // Update user
        $user->update([
            'plan_id' => $plan->id,
            'billing_cycle_start' => now(),
            'messages_this_month' => 0,
        ]);

        // Create/Update subscription
        Subscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan_id' => $plan->id,
                'payment_gateway' => 'manual',
                'status' => 'active',
                'current_period_start' => now(),
                'current_period_end' => now()->addMonth(),
            ]
        );

        $user->refresh();
        $this->info("✓ Successfully assigned {$plan->name} to {$user->email}");
        $this->info("  User plan_id: {$user->plan_id}");
        $this->info("  Plan name: {$user->plan->name}");

        return 0;
    }
}
