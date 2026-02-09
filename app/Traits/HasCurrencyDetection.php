<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Torann\GeoIP\Facades\GeoIP;

trait HasCurrencyDetection
{
    /**
     * Detect and return the currency based on IP or manual override
     */
    protected function detectCurrency(Request $request): string
    {
        $ip = $request->ip();

        // Handle manual override
        if ($request->has('currency')) {
            $manual = strtoupper($request->currency);
            if (in_array($manual, ['INR', 'USD'])) {
                Session::put('currency', $manual);
                return $manual;
            }
        }

        // Return session currency if already set
        if (Session::has('currency')) {
            return Session::get('currency');
        }

        // Auto-detect based on IP
        try {
            // Local IP detection for development
            $isLocal = ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.') || str_starts_with($ip, '172.'));

            if ($isLocal) {
                $currency = 'INR';
            } else {
                $location = GeoIP::getLocation($ip);
                $currency = ($location->iso_code === 'IN') ? 'INR' : 'USD';
            }
        } catch (\Exception $e) {
            $currency = 'USD';
        }

        Session::put('currency', $currency);
        return $currency;
    }
}
