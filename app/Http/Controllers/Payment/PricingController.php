<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Correct import
use Torann\GeoIP\Facades\GeoIP;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        $ip = $request->ip();
        \Illuminate\Support\Facades\Log::info('Pricing access', ['ip' => $ip, 'session_currency' => Session::get('currency')]);

        // Auto-detect based on IP
        try {
            $isLocal = ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.') || str_starts_with($ip, '172.'));

            if ($isLocal) {
                $currency = 'INR';
            } elseif (Session::has('currency')) {
                $currency = Session::get('currency');
            } else {
                $location = GeoIP::getLocation($ip);
                $currency = ($location->iso_code === 'IN') ? 'INR' : 'INR'; // Default to INR if not specific logic
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('GeoIP Error: ' . $e->getMessage());
            $currency = Session::get('currency', 'INR');
        }

        // Handle manual override
        if ($request->has('currency')) {
            $manual = strtoupper($request->currency);
            if (in_array($manual, ['INR', 'USD'])) {
                $currency = $manual;
            }
        }

        Session::put('currency', $currency);

        // 3. Get all active plans sorted
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($plan) {
                // Dynamically update features to match actual limits
                if (is_array($plan->features)) {
                    $features = $plan->features;
                    foreach ($features as $key => $feature) {
                        // Messages Limit
                        if (preg_match('/Messages\s*\/\s*month/i', $feature)) {
                            $limit = $plan->max_messages_per_month == -1 ? 'Unlimited' : number_format($plan->max_messages_per_month);
                            $features[$key] = $limit . ' Messages/month';
                        }
                        // Chatbots Limit
                        if (preg_match('/\d+\s*Chatbots/i', $feature) || preg_match('/Chatbots/i', $feature)) {
                            $limit = $plan->max_chatbots == -1 ? 'Unlimited' : number_format($plan->max_chatbots);
                            $features[$key] = $limit . ' Chatbots';
                        }
                    }
                    $plan->features = $features;
                }
                return $plan;
            });

        // Get add-ons grouped by type
        $addons = \App\Models\Addon::active()
            ->orderBy('type')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('type');

        return view('pricing', compact('plans', 'currency', 'addons'));
    }
}
