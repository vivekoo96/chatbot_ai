<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\UserAddon;
use App\Services\AddonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddonController extends Controller
{
    protected $addonService;

    public function __construct(AddonService $addonService)
    {
        $this->addonService = $addonService;
    }

    /**
     * Show add-ons marketplace
     */
    public function index()
    {
        $user = Auth::user();
        $currency = session('currency', 'INR');

        $addons = Addon::active()
            ->orderBy('type')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('type');

        return view('addons.index', [
            'addons' => $addons,
            'currency' => $currency,
            'recommendedModels' => config('addons.recommended_models', []),
        ]);
    }

    /**
     * Checkout page for add-on
     */
    public function checkout(Addon $addon)
    {
        $user = Auth::user();
        $currency = session('currency', 'INR');

        // Create Razorpay order
        $result = $this->addonService->purchaseAddon($user, $addon, $currency);

        if (!$result['success']) {
            return back()->with('error', 'Error creating payment order: ' . $result['error']);
        }

        return view('addons.checkout', [
            'addon' => $addon,
            'order_id' => $result['order_id'],
            'amount' => $result['amount'],
            'currency' => $currency,
            'key' => config('services.razorpay.key'),
            'user' => $user,
        ]);
    }

    /**
     * Verify payment and activate add-on
     */
    public function verify(Request $request)
    {
        $user = Auth::user();

        try {
            // Validate required fields
            if (
                !isset($request->razorpay_payment_id) ||
                !isset($request->razorpay_order_id) ||
                !isset($request->razorpay_signature)
            ) {
                return redirect()->route('addons.index')->with('error', 'Invalid payment data');
            }

            // Verify signature
            $api = new \Razorpay\Api\Api(
                config('services.razorpay.key'),
                config('services.razorpay.secret')
            );

            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // Get addon
            $addonId = $request->addon_id;
            $addon = Addon::find($addonId);

            if (!$addon) {
                return redirect()->route('addons.index')->with('error', 'Add-on not found');
            }

            // Activate add-on
            $currency = session('currency', 'INR');
            $amountPaid = (float) ($currency === 'INR' ? $addon->price_inr : $addon->price_usd);

            $userAddon = $this->addonService->activateAddon(
                $user,
                $addon,
                $request->razorpay_payment_id,
                $request->razorpay_order_id,
                $amountPaid
            );

            // Send invoice email
            $user->notify(new \App\Notifications\PaymentSuccessful($userAddon, $amountPaid, 'addon'));

            return redirect()->route('my-addons')->with('success', 'Add-on purchased successfully!');

        } catch (\Razorpay\Api\Errors\SignatureVerificationError $e) {
            Log::error('Add-on payment verification failed', ['error' => $e->getMessage()]);
            return redirect()->route('addons.index')->with('error', 'Payment verification failed');
        } catch (\Exception $e) {
            Log::error('Add-on activation error', ['error' => $e->getMessage()]);
            return redirect()->route('addons.index')->with('error', 'Error activating add-on');
        }
    }

    /**
     * Show user's purchased add-ons
     */
    public function myAddons()
    {
        $user = Auth::user();

        $activeAddons = UserAddon::where('user_id', $user->id)
            ->with('addon')
            ->active()
            ->orderBy('expires_at', 'asc')
            ->get();

        $expiredAddons = UserAddon::where('user_id', $user->id)
            ->with('addon')
            ->whereIn('status', ['expired', 'consumed'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.my-addons', [
            'activeAddons' => $activeAddons,
            'expiredAddons' => $expiredAddons,
        ]);
    }
}
