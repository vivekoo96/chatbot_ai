<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    protected $razorpayService;

    public function __construct(\App\Services\RazorpayService $razorpayService)
    {
        $this->razorpayService = $razorpayService;
    }

    public function index()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:plans,slug',
            'svg_icon' => 'nullable|string',
            'price_inr' => 'required|numeric|min:0',
            'price_usd' => 'required|numeric|min:0',
            'max_chatbots' => 'required|integer',
            'max_messages_per_month' => 'required|integer',
            'max_image_uploads_per_month' => 'required|integer',
            'max_voice_minutes_per_month' => 'required|integer',
            'max_team_users' => 'required|integer',
            'chat_history_days' => 'required|integer',
            'support_level' => 'required|string|in:email,priority,dedicated',
            'is_active' => 'boolean',
        ]);

        $flags = [
            'has_api_access',
            'has_analytics_dashboard',
            'has_advanced_analytics',
            'has_branding_removal',
            'has_lead_capture',
            'has_custom_ai_guidance',
            'has_advanced_rules',
            'has_role_based_access',
            'has_team_access',
        ];

        foreach ($flags as $flag) {
            $validated[$flag] = $request->has($flag);
        }

        if ($request->filled('features_json')) {
            $features = array_filter(array_map('trim', explode("\n", $request->input('features_json'))));
            $validated['features'] = array_values($features);
        }

        $plan = Plan::create($validated);

        // Sync with Razorpay if paid
        if ($plan->price_inr > 0) {
            $razorpayPlanId = $this->razorpayService->createPlan($plan);
            if ($razorpayPlanId) {
                $plan->update(['razorpay_plan_id' => $razorpayPlanId]);
            }
        }

        return redirect()->route('admin.plans.index')->with('success', 'Plan created and synced with Razorpay!');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $oldPrice = (float) $plan->price_inr;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'svg_icon' => 'nullable|string',
            'price_inr' => 'required|numeric|min:0',
            'price_usd' => 'required|numeric|min:0',
            'max_chatbots' => 'required|integer',
            'max_messages_per_month' => 'required|integer',
            'max_image_uploads_per_month' => 'required|integer',
            'max_voice_minutes_per_month' => 'required|integer',
            'max_team_users' => 'required|integer',
            'chat_history_days' => 'required|integer',
            'support_level' => 'required|string|in:email,priority,dedicated',
            'is_active' => 'boolean',
        ]);

        $flags = [
            'has_api_access',
            'has_analytics_dashboard',
            'has_advanced_analytics',
            'has_branding_removal',
            'has_lead_capture',
            'has_custom_ai_guidance',
            'has_advanced_rules',
            'has_role_based_access',
            'has_team_access',
            'is_active'
        ];

        foreach ($flags as $flag) {
            $validated[$flag] = $request->has($flag);
        }

        if ($request->filled('features_json')) {
            $features = array_filter(array_map('trim', explode("\n", $request->input('features_json'))));
            $plan->features = array_values($features);
        }

        $plan->update($validated);

        // If price changed OR the plan has no Razorpay ID, we MUST create/sync it
        if ($plan->price_inr > 0 && (!$plan->razorpay_plan_id || abs($oldPrice - (float) $plan->price_inr) > 0.01)) {
            Log::info('Syncing plan with Razorpay (Price changed or ID missing)', ['plan_id' => $plan->id]);
            $razorpayPlanId = $this->razorpayService->createPlan($plan);
            if ($razorpayPlanId) {
                $plan->update(['razorpay_plan_id' => $razorpayPlanId]);
            }
        }

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated and synced with Razorpay!');
    }
}
