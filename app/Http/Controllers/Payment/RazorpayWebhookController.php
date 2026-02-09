<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WebhookEvent;
use App\Notifications\PaymentFailed;
use App\Notifications\PaymentSuccessful;
use App\Notifications\SubscriptionActivated;
use App\Notifications\SubscriptionCancelled;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayWebhookController extends Controller
{
    protected $api;
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');
        $this->api = new Api($key, $secret);
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle incoming webhook from Razorpay
     */
    public function handle(Request $request)
    {
        Log::info('Razorpay Webhook Received', [
            'event' => $request->input('event'),
            'payload_keys' => array_keys($request->all())
        ]);

        // Verify webhook signature
        if (!$this->verifySignature($request)) {
            Log::error('Razorpay Webhook: Invalid signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $payload = $request->all();
        $event = $payload['event'] ?? null;

        if (!$event) {
            Log::error('Razorpay Webhook: No event type');
            return response()->json(['error' => 'No event type'], 400);
        }

        // Log webhook event
        $webhookEvent = WebhookEvent::create([
            'event_id' => $payload['payload']['payment']['entity']['id'] ?? $payload['payload']['subscription']['entity']['id'] ?? uniqid(),
            'event_type' => $event,
            'entity_type' => $payload['entity'] ?? null,
            'entity_id' => $payload['payload']['subscription']['entity']['id'] ?? $payload['payload']['payment']['entity']['id'] ?? null,
            'payload' => $payload,
            'status' => 'pending',
        ]);

        try {
            $webhookEvent->markAsProcessing();

            // Route to appropriate handler
            $handled = match ($event) {
                'subscription.activated' => $this->handleSubscriptionActivated($payload),
                'subscription.charged' => $this->handleSubscriptionCharged($payload),
                'invoice.paid' => $this->handleInvoicePaid($payload),
                'payment.failed', 'invoice.payment_failed' => $this->handlePaymentFailed($payload),
                'subscription.cancelled' => $this->handleSubscriptionCancelled($payload),
                'subscription.paused' => $this->handleSubscriptionPaused($payload),
                'subscription.resumed' => $this->handleSubscriptionResumed($payload),
                'subscription.completed' => $this->handleSubscriptionCompleted($payload),
                default => $this->handleUnknownEvent($event, $payload),
            };

            if ($handled) {
                $webhookEvent->markAsProcessed();
                return response()->json(['status' => 'success']);
            }

            $webhookEvent->markAsFailed('Handler returned false');
            return response()->json(['status' => 'ignored'], 200);

        } catch (\Exception $e) {
            Log::error('Razorpay Webhook Processing Error', [
                'event' => $event,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $webhookEvent->markAsFailed($e->getMessage());
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle subscription.activated event
     */
    protected function handleSubscriptionActivated(array $payload): bool
    {
        $entity = $payload['payload']['subscription']['entity'] ?? null;
        if (!$entity)
            return false;

        $subscription = Subscription::where('razorpay_subscription_id', $entity['id'])->first();
        if (!$subscription) {
            Log::warning('Subscription not found for activation', ['razorpay_id' => $entity['id']]);
            return false;
        }

        // Activate subscription
        $subscription->update([
            'status' => 'active',
            'current_period_start' => now()->setTimestamp($entity['current_start']),
            'current_period_end' => now()->setTimestamp($entity['current_end']),
            'next_billing_at' => isset($entity['charge_at']) ? now()->setTimestamp($entity['charge_at']) : null,
        ]);

        // Update user's plan
        $subscription->user->update([
            'plan_id' => $subscription->plan_id,
            'billing_cycle_start' => now(),
            'messages_this_month' => 0,
        ]);

        // Send activation email
        $subscription->user->notify(new SubscriptionActivated($subscription));

        Log::info('Subscription Activated', ['subscription_id' => $subscription->id]);
        return true;
    }

    /**
     * Handle subscription.charged or invoice.paid event
     */
    protected function handleSubscriptionCharged(array $payload): bool
    {
        return $this->handleInvoicePaid($payload);
    }

    protected function handleInvoicePaid(array $payload): bool
    {
        $entity = $payload['payload']['payment']['entity'] ?? $payload['payload']['invoice']['entity'] ?? null;
        if (!$entity)
            return false;

        $subscriptionId = $entity['subscription_id'] ?? $entity['notes']['subscription_id'] ?? null;
        if (!$subscriptionId)
            return false;

        $subscription = Subscription::where('razorpay_subscription_id', $subscriptionId)->first();
        if (!$subscription) {
            Log::warning('Subscription not found for payment', ['razorpay_id' => $subscriptionId]);
            return false;
        }

        // Reset failed payments and extend period
        $subscription->resetFailedPayments();
        $subscription->update([
            'status' => 'active',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
            'next_billing_at' => now()->addMonth(),
        ]);

        // Send payment success email
        $subscription->user->notify(new PaymentSuccessful($subscription, $entity['amount'] / 100));

        Log::info('Payment Successful', [
            'subscription_id' => $subscription->id,
            'amount' => $entity['amount'] / 100
        ]);

        return true;
    }

    /**
     * Handle payment.failed or invoice.payment_failed event
     */
    protected function handlePaymentFailed(array $payload): bool
    {
        $entity = $payload['payload']['payment']['entity'] ?? $payload['payload']['invoice']['entity'] ?? null;
        if (!$entity)
            return false;

        $subscriptionId = $entity['subscription_id'] ?? $entity['notes']['subscription_id'] ?? null;
        if (!$subscriptionId)
            return false;

        $subscription = Subscription::where('razorpay_subscription_id', $subscriptionId)->first();
        if (!$subscription) {
            Log::warning('Subscription not found for failed payment', ['razorpay_id' => $subscriptionId]);
            return false;
        }

        // Increment failed payment count
        $subscription->incrementFailedPayments();

        // Send failed payment notification
        $subscription->user->notify(new PaymentFailed(
            $subscription,
            $subscription->failed_payment_count,
            $subscription->grace_period_ends_at
        ));

        Log::warning('Payment Failed', [
            'subscription_id' => $subscription->id,
            'failed_count' => $subscription->failed_payment_count,
            'grace_period_ends' => $subscription->grace_period_ends_at
        ]);

        return true;
    }

    /**
     * Handle subscription.cancelled event
     */
    protected function handleSubscriptionCancelled(array $payload): bool
    {
        $entity = $payload['payload']['subscription']['entity'] ?? null;
        if (!$entity)
            return false;

        $subscription = Subscription::where('razorpay_subscription_id', $entity['id'])->first();
        if (!$subscription) {
            Log::warning('Subscription not found for cancellation', ['razorpay_id' => $entity['id']]);
            return false;
        }

        $subscription->cancel();

        // Send cancellation email
        $subscription->user->notify(new SubscriptionCancelled($subscription));

        Log::info('Subscription Cancelled', ['subscription_id' => $subscription->id]);
        return true;
    }

    /**
     * Handle subscription.paused event
     */
    protected function handleSubscriptionPaused(array $payload): bool
    {
        $entity = $payload['payload']['subscription']['entity'] ?? null;
        if (!$entity)
            return false;

        $subscription = Subscription::where('razorpay_subscription_id', $entity['id'])->first();
        if (!$subscription)
            return false;

        $subscription->update(['status' => 'paused']);

        Log::info('Subscription Paused', ['subscription_id' => $subscription->id]);
        return true;
    }

    /**
     * Handle subscription.resumed event
     */
    protected function handleSubscriptionResumed(array $payload): bool
    {
        $entity = $payload['payload']['subscription']['entity'] ?? null;
        if (!$entity)
            return false;

        $subscription = Subscription::where('razorpay_subscription_id', $entity['id'])->first();
        if (!$subscription)
            return false;

        $subscription->resume();

        Log::info('Subscription Resumed', ['subscription_id' => $subscription->id]);
        return true;
    }

    /**
     * Handle subscription.completed event
     */
    protected function handleSubscriptionCompleted(array $payload): bool
    {
        $entity = $payload['payload']['subscription']['entity'] ?? null;
        if (!$entity)
            return false;

        $subscription = Subscription::where('razorpay_subscription_id', $entity['id'])->first();
        if (!$subscription)
            return false;

        $subscription->update(['status' => 'expired']);

        Log::info('Subscription Completed', ['subscription_id' => $subscription->id]);
        return true;
    }

    /**
     * Handle unknown events
     */
    protected function handleUnknownEvent(string $event, array $payload): bool
    {
        Log::info('Unknown Razorpay Webhook Event', ['event' => $event]);
        return false;
    }

    /**
     * Verify webhook signature
     */
    protected function verifySignature(Request $request): bool
    {
        $webhookSecret = config('services.razorpay.webhook_secret');

        // Skip verification if no secret configured (development only)
        if (empty($webhookSecret)) {
            Log::warning('Razorpay webhook secret not configured - skipping verification');
            return true;
        }

        $webhookSignature = $request->header('X-Razorpay-Signature');
        $webhookBody = $request->getContent();

        try {
            $this->api->utility->verifyWebhookSignature($webhookBody, $webhookSignature, $webhookSecret);
            return true;
        } catch (SignatureVerificationError $e) {
            Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
