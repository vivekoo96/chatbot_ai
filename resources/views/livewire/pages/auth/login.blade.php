<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;
    public bool $showPassword = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (auth()->user()->is_super_admin) {
            $this->redirect(route('admin.dashboard', absolute: false), navigate: true);
        } else {
            // Force regular users to standard dashboard to avoid 403 on intended admin routes
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        }
    }
}; ?>

<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Welcome back</h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Sign in to manage your AI assistants and view analytics.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email Address')"
                class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider" />
            <x-text-input wire:model="form.email" id="email"
                class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5"
                type="email" name="email" required autofocus placeholder="name@company.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')"
                    class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline"
                        href="{{ route('password.request') }}" wire:navigate>
                        Forgot Password?
                    </a>
                @endif
            </div>
            <div class="relative w-full group">
                <x-text-input wire:model="form.password" id="password"
                    class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5 pr-12"
                    :type="$showPassword ? 'text' : 'password'" name="password" required placeholder="••••••••" />
                <button type="button" wire:click="$toggle('showPassword')"
                    class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                    @if($showPassword)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    @endif
                </button>
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember" class="inline-flex items-center cursor-pointer group">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="w-5 h-5 rounded-lg border-slate-200 dark:border-slate-700 text-indigo-600 shadow-sm focus:ring-indigo-500/20 transition-all duration-200 cursor-pointer">
                <span
                    class="ms-3 text-sm font-medium text-slate-500 dark:text-slate-400 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition-colors">{{ __('Stay signed in') }}</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 mt-8 pt-6 border-t border-slate-100 dark:border-slate-800/50">
            <button type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold px-10 py-4 rounded-2xl shadow-xl shadow-indigo-500/25 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                <span>Sign In to Dashboard</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>

            <p class="text-center text-sm font-medium text-slate-500">
                New to ChatBoat?
                <a href="{{ route('register') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-bold"
                    wire:navigate>Create an account</a>
            </p>
        </div>
    </form>
</div>