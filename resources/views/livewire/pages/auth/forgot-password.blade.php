<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Reset Password</h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            {{ __('Forgot your password? No problem. Enter your email address and we will send you a reset link.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-6">
        <!-- Email Address -->
        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email Address')"
                class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider" />
            <x-text-input wire:model="email" id="email"
                class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5"
                type="email" name="email" required autofocus placeholder="name@company.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="flex flex-col gap-4 mt-8 pt-6 border-t border-slate-100 dark:border-slate-800/50">
            <button type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold px-10 py-4 rounded-2xl shadow-xl shadow-indigo-500/25 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                <span>Email Password Reset Link</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </button>

            <p class="text-center text-sm font-medium text-slate-500">
                Remember your password?
                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-bold"
                    wire:navigate>Back to Login</a>
            </p>
        </div>
    </form>
</div>