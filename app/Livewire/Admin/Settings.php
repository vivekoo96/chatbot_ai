<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class Settings extends Component
{
    public $site_name;
    public $support_email;
    public $openai_key;
    public $gst_percent;
    public $webhook_url;
    public $privacy_policy;
    public $terms_conditions;

    // Mail Settings
    public $mail_host;
    public $mail_port;
    public $mail_username;
    public $mail_password;
    public $mail_encryption;
    public $mail_from_address;
    public $mail_from_name;

    // Razorpay Settings
    public $razorpay_key;
    public $razorpay_secret;

    public function mount()
    {
        $this->site_name = Setting::where('key', 'site_name')->first()?->value ?? config('app.name');
        $this->support_email = Setting::where('key', 'support_email')->first()?->value ?? config('mail.from.address');
        $this->openai_key = Setting::where('key', 'openai_key')->first()?->value ?? config('openai.api_key');

        // Mail
        $this->mail_host = Setting::where('key', 'mail_host')->first()?->value ?? config('mail.mailers.smtp.host');
        $this->mail_port = Setting::where('key', 'mail_port')->first()?->value ?? config('mail.mailers.smtp.port');
        $this->mail_username = Setting::where('key', 'mail_username')->first()?->value ?? config('mail.mailers.smtp.username');
        $this->mail_password = Setting::where('key', 'mail_password')->first()?->value ?? config('mail.mailers.smtp.password');
        $this->mail_encryption = Setting::where('key', 'mail_encryption')->first()?->value ?? config('mail.mailers.smtp.encryption');
        $this->mail_from_address = Setting::where('key', 'mail_from_address')->first()?->value ?? config('mail.from.address');
        $this->mail_from_name = Setting::where('key', 'mail_from_name')->first()?->value ?? config('mail.from.name');

        // Razorpay
        $this->razorpay_key = Setting::where('key', 'razorpay_key')->first()?->value ?? config('services.razorpay.key');
        $this->razorpay_secret = Setting::where('key', 'razorpay_secret')->first()?->value ?? config('services.razorpay.secret');

        // GST
        $this->gst_percent = Setting::where('key', 'gst_percent')->first()?->value ?? 0;

        // Webhook
        $this->webhook_url = Setting::where('key', 'webhook_url')->first()?->value ?? '';

        // Legal
        $this->privacy_policy = Setting::where('key', 'privacy_policy')->first()?->value ?? '';
        $this->terms_conditions = Setting::where('key', 'terms_conditions')->first()?->value ?? '';
    }

    public function save()
    {
        $this->validate([
            'site_name' => 'required|string|max:255',
            'support_email' => 'required|email|max:255',
            'openai_key' => 'required|string|max:255',
            'mail_host' => 'required|string',
            'mail_port' => 'required|numeric',
            'mail_username' => 'required|string',
            'mail_password' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
            'razorpay_key' => 'required|string',
            'razorpay_secret' => 'required|string',
            'gst_percent' => 'required|numeric|min:0|max:100',
            'webhook_url' => 'nullable|url|max:2000',
            'privacy_policy' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
        ]);

        $settings = [
            'site_name' => $this->site_name,
            'support_email' => $this->support_email,
            'openai_key' => $this->openai_key,
            'mail_host' => $this->mail_host,
            'mail_port' => $this->mail_port,
            'mail_username' => $this->mail_username,
            'mail_password' => $this->mail_password,
            'mail_encryption' => $this->mail_encryption,
            'mail_from_address' => $this->mail_from_address,
            'mail_from_name' => $this->mail_from_name,
            'razorpay_key' => $this->razorpay_key,
            'razorpay_secret' => $this->razorpay_secret,
            'gst_percent' => $this->gst_percent,
            'webhook_url' => $this->webhook_url,
            'privacy_policy' => $this->privacy_policy,
            'terms_conditions' => $this->terms_conditions,
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        session()->flash('message', 'Settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('components.admin-layout');
    }
}
