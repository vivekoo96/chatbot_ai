<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Payment\PricingController;
use App\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontendController;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/features', [FrontendController::class, 'features'])->name('features');
Route::get('/about', [FrontendController::class, 'about'])->name('about');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::get('/privacy-policy', [FrontendController::class, 'privacy'])->name('privacy');
Route::get('/terms', [FrontendController::class, 'terms'])->name('terms');
Route::get('/docs', [FrontendController::class, 'docs'])->name('docs');

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

// Widget Routes (Public)
Route::get('/widget/embed.js', [WidgetController::class, 'embedScript'])
    ->name('widget.embed');
Route::get('/widget/{token}', [WidgetController::class, 'iframe'])
    ->name('widget.iframe');

// Webhook Route (Public - no auth required)
Route::post('/webhooks/razorpay', [\App\Http\Controllers\Payment\RazorpayWebhookController::class, 'handle'])
    ->name('webhooks.razorpay');

// Payment Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout-test', function () {
        return view('payment.checkout-test');
    })->name('checkout-test');
    Route::get('/checkout/{plan}', [\App\Http\Controllers\Payment\RazorpayController::class, 'checkout'])->name('checkout');
    Route::post('/subscription/cancel', [\App\Http\Controllers\Payment\RazorpayController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/subscription/resume', [\App\Http\Controllers\Payment\RazorpayController::class, 'resume'])->name('subscription.resume');
    Route::post('/subscription/verify', [\App\Http\Controllers\Payment\RazorpayController::class, 'verify'])->name('subscription.verify');

    // Add-ons Routes
    Route::get('/addons', [\App\Http\Controllers\AddonController::class, 'index'])->name('addons.index');
    Route::get('/addons/{addon}/checkout', [\App\Http\Controllers\AddonController::class, 'checkout'])->name('addons.checkout');
    Route::post('/addons/verify', [\App\Http\Controllers\AddonController::class, 'verify'])->name('addons.verify');
    Route::get('/dashboard/my-addons', [\App\Http\Controllers\AddonController::class, 'myAddons'])->name('my-addons');
    Route::get('/billing', [\App\Http\Controllers\BillingController::class, 'index'])->name('billing');
    Route::get('/billing/invoice/{type}/{id}/download', [\App\Http\Controllers\InvoiceController::class, 'download'])->name('billing.invoice.download');


    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('analytics', 'analytics')->name('analytics');
    Route::view('activities', 'activities')->name('activities');
    Route::view('profile', 'profile')->name('profile');
    Route::get('live-chat', \App\Livewire\Dashboard\LiveChat::class)->name('live-chat');
    Route::get('leads', \App\Livewire\Dashboard\Leads::class)->name('leads');
    Route::view('team', 'team')->name('team');


    Route::match(['get', 'post'], 'logout', function (\App\Livewire\Actions\Logout $logout) {
        $logout();
        return redirect('/');
    })->name('logout');

    // Protected by Plan Limits
    Route::middleware([\App\Http\Middleware\CheckPlanLimits::class . ':chatbot'])->group(function () {
        // Chatbot creation logic is currently inside Livewire components
        // The middleware should be applied in the Livewire component or the route
    });
});

// Admin Routes (Super Admin Only)
Route::middleware(['auth', 'verified', \App\Http\Middleware\EnsureSuperAdmin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('chatbots', [AdminDashboardController::class, 'chatbots'])->name('chatbots');
        Route::get('payments', [AdminDashboardController::class, 'payments'])->name('payments');

        // Plan Management
        Route::get('plans', [\App\Http\Controllers\Admin\PlanController::class, 'index'])->name('plans.index');
        Route::get('plans/create', [\App\Http\Controllers\Admin\PlanController::class, 'create'])->name('plans.create');
        Route::post('plans', [\App\Http\Controllers\Admin\PlanController::class, 'store'])->name('plans.store');
        Route::get('plans/{plan}/edit', [\App\Http\Controllers\Admin\PlanController::class, 'edit'])->name('plans.edit');
        Route::put('plans/{plan}', [\App\Http\Controllers\Admin\PlanController::class, 'update'])->name('plans.update');

        // Add-on Management
        Route::resource('addons', \App\Http\Controllers\Admin\AddonController::class);

        // User Billing Management
        Route::get('user-billing', [\App\Http\Controllers\Admin\UserBillingController::class, 'index'])->name('user-billing');
        Route::get('user-billing/{user}', [\App\Http\Controllers\Admin\UserBillingController::class, 'show'])->name('user-billing.show');

        Route::get('inquiries', \App\Livewire\Admin\ContactInquiries::class)->name('inquiries');
        Route::get('settings', \App\Livewire\Admin\Settings::class)->name('settings');
    });

Route::get('admin/stop-impersonating', [\App\Http\Controllers\Admin\ImpersonationController::class, 'stop'])
    ->middleware(['auth'])
    ->name('admin.stop-impersonating');

require __DIR__ . '/auth.php';

Route::post('/session-keep-alive', function () {
    return response()->json(['status' => 'alive']);
})->name('session.keep-alive');