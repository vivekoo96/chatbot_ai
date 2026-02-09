<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Torann\GeoIP\Facades\GeoIP;

class FrontendController extends Controller
{
    use \App\Traits\HasCurrencyDetection;

    public function index(Request $request)
    {
        $currency = $this->detectCurrency($request);

        $plans = Plan::where('is_active', true)->orderBy('sort_order')->take(3)->get(); // Show top 3 plans
        $addons = \App\Models\Addon::where('is_active', true)->get();

        // Fetch a demo chatbot (e.g., the first one created)
        $demoBot = \App\Models\Chatbot::first();

        return view('welcome', compact('plans', 'currency', 'demoBot', 'addons'));
    }

    public function features()
    {
        return view('frontend.features');
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function privacy()
    {
        $content = \App\Models\Setting::getValue('privacy_policy');
        return view('frontend.privacy', compact('content'));
    }

    public function terms()
    {
        $content = \App\Models\Setting::getValue('terms_conditions');
        return view('frontend.terms', compact('content'));
    }

    public function docs()
    {
        return view('frontend.docs');
    }
}
