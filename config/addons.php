<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Add-ons Configuration
    |--------------------------------------------------------------------------
    */

    // Add-ons expire at end of billing cycle
    'expires_with_billing_cycle' => true,

    // Fixed expiry days (if not using billing cycle)
    'expiry_days' => 30,

    // Recommended AI models
    'recommended_models' => [
        'text' => 'gpt-4o-mini',
        'image' => 'gpt-4o-mini',
        'voice' => 'gpt-4o-mini-audio-preview',
    ],
];
