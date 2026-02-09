<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Subscription Configuration
    |--------------------------------------------------------------------------
    */

    // Maximum number of failed payments before starting grace period
    'max_failed_payments' => env('MAX_FAILED_PAYMENTS', 3),

    // Grace period duration in days after max failed payments
    'grace_period_days' => env('SUBSCRIPTION_GRACE_PERIOD_DAYS', 5),

    // Days before billing to send payment reminder
    'payment_reminder_days' => env('PAYMENT_REMINDER_DAYS', 3),

    // Auto-renew enabled by default
    'auto_renew_default' => true,
];
