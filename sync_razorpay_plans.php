<?php

use App\Models\Plan;
use Razorpay\Api\Api;

$key = config('services.razorpay.key');
$secret = config('services.razorpay.secret');
$api = new Api($key, $secret);

echo "Fetching plans from Razorpay...\n";
$remotePlans = $api->plan->all(['count' => 100]);
$remotePlanMap = []; // Name -> ID

foreach ($remotePlans->items as $rp) {
    echo "Found Remote Plan: {$rp->item->name} ({$rp->id})\n";
    $remotePlanMap[$rp->item->name] = $rp->id;
}

$localPlans = Plan::all();

foreach ($localPlans as $plan) {
    if ($plan->price_inr <= 0) {
        echo "Skipping free/custom plan: {$plan->name}\n";
        continue;
    }

    echo "Processing Local Plan: {$plan->name}...\n";

    $razorpayId = $remotePlanMap[$plan->name] ?? null;

    if ($razorpayId) {
        echo " - Matched with existing Razorpay Plan: $razorpayId\n";
        $plan->update(['razorpay_plan_id' => $razorpayId]);
    } else {
        echo " - Creating new Plan in Razorpay...\n";
        try {
            $created = $api->plan->create([
                'period' => 'monthly',
                'interval' => 1,
                'item' => [
                    'name' => $plan->name,
                    'amount' => (int) ($plan->price_inr * 100),
                    'currency' => 'INR',
                    'description' => $plan->name . ' Subscription'
                ],
                'notes' => [
                    'local_id' => $plan->id
                ]
            ]);
            $razorpayId = $created->id;
            echo " - Created! ID: $razorpayId\n";
            $plan->update(['razorpay_plan_id' => $razorpayId]);
        } catch (\Exception $e) {
            echo " - Error creating plan: " . $e->getMessage() . "\n";
        }
    }
}

echo "Sync complete.\n";
