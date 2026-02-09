<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('subscriptions:reset-usage', function () {
    $this->info('Resetting monthly message usage for all users...');
    \App\Models\User::query()->update(['messages_this_month' => 0]);
    $this->info('Usage reset successfully.');
})->purpose('Reset monthly message usage for all users');

// Schedule the reset to run on the 1st of every month
Illuminate\Support\Facades\Schedule::command('subscriptions:reset-usage')->monthly();
