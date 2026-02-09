<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load dynamic settings from database
        try {
            if (class_exists(\App\Models\Setting::class) && \Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Setting::all()->pluck('value', 'key');

                // Brand Settings
                if ($settings->get('site_name')) {
                    config(['app.name' => $settings->get('site_name')]);
                }

                // Mail Settings
                if ($settings->get('mail_host')) {
                    $mailConfig = [
                        'mail.mailers.smtp.host' => $settings->get('mail_host'),
                        'mail.mailers.smtp.port' => $settings->get('mail_port') ?: config('mail.mailers.smtp.port'),
                        'mail.mailers.smtp.username' => $settings->get('mail_username') ?: config('mail.mailers.smtp.username'),
                        'mail.mailers.smtp.password' => $settings->get('mail_password') ?: config('mail.mailers.smtp.password'),
                        'mail.mailers.smtp.encryption' => $settings->get('mail_encryption') ?: config('mail.mailers.smtp.encryption'),
                    ];

                    $fromAddress = $settings->get('mail_from_address') ?: $settings->get('support_email');
                    if ($fromAddress) {
                        $mailConfig['mail.from.address'] = $fromAddress;
                    }

                    if ($settings->get('mail_from_name')) {
                        $mailConfig['mail.from.name'] = $settings->get('mail_from_name');
                    } elseif ($settings->get('site_name')) {
                        $mailConfig['mail.from.name'] = $settings->get('site_name');
                    }

                    config($mailConfig);
                } elseif ($settings->get('support_email')) {
                    config(['mail.from.address' => $settings->get('support_email')]);
                }


                // AI Engine Settings
                if ($settings->get('openai_key')) {
                    config(['services.openai.key' => $settings->get('openai_key')]);
                    config(['openai.api_key' => $settings->get('openai_key')]);
                }

                // Payment Gateway (Razorpay) Settings
                if ($settings->get('razorpay_key')) {
                    config([
                        'services.razorpay.key' => $settings->get('razorpay_key'),
                        'services.razorpay.secret' => $settings->get('razorpay_secret'),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silently fail if DB is not ready
        }
    }
}
