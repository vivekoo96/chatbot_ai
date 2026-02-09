<?php

use App\Http\Controllers\WidgetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Widget Public API (No authentication required)
Route::post('/widget/chat', [WidgetController::class, 'chat'])
    ->name('widget.chat');

// Webhook Routes
Route::post('/webhook/razorpay', [\App\Http\Controllers\Payment\RazorpayWebhookController::class, 'handle']);
