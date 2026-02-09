<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $otp = '';

    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Verify the provided OTP.
     */
    public function verifyOtp(): void
    {
        $this->validate(['otp' => 'required|string|size:6']);

        $user = Auth::user();

        if ($user->verification_otp === $this->otp && $user->verification_otp_expires_at->isFuture()) {
            $user->markEmailAsVerified();

            $user->update([
                'verification_otp' => null,
                'verification_otp_expires_at' => null,
            ]);

            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        } else {
            $this->addError('otp', 'Invalid or expired verification code.');
        }
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="relative">
    <!-- Status Message (Toast style to prevent layout jump) -->
    <div class="absolute -top-16 left-0 right-0 z-50">
        @if (session('status') == 'verification-link-sent')
            <div
                class="mx-auto max-w-sm p-4 rounded-2xl bg-emerald-500 text-white shadow-2xl flex items-center gap-3 animate-in fade-in slide-in-from-top-8 duration-500">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-xs font-bold">{{ __('Link Resent Successfully!') }}</p>
                    <p class="text-[10px] opacity-90">{{ __('Check your inbox and spam folder.') }}</p>
                </div>
            </div>
        @endif
    </div>

    <div class="space-y-10">
        <!-- Check Email Message -->
        <div class="text-center">
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-indigo-50 dark:bg-indigo-500/10 rounded-3xl mb-6 group transition-transform duration-500 hover:scale-110">
                <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400 animate-bounce" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h2 class="text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-3">Check your inbox</h2>
            <p class="text-slate-600 dark:text-slate-400 max-w-sm mx-auto leading-relaxed">
                {{ __('We\'ve sent a 6-digit verification code to your email address. Please enter it below to activate your account.') }}
            </p>
        </div>

        <!-- OTP Input Section -->
        <div class="space-y-6">
            <div class="relative">
                <input wire:model.defer="otp" type="text" maxlength="6"
                    class="w-full text-center text-4xl font-black tracking-[0.5em] py-5 bg-white dark:bg-slate-900/40 border-2 border-slate-100 dark:border-slate-800 focus:border-indigo-500 focus:ring-0 rounded-2xl transition-all duration-300 placeholder:text-slate-200 dark:placeholder:text-slate-800"
                    placeholder="000000">
                @error('otp')
                    <p class="text-center mt-2 text-xs font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>
                @enderror
            </div>

            <button wire:click="verifyOtp" wire:loading.attr="disabled"
                class="group relative w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold py-5 rounded-2xl shadow-xl transition-all duration-300 hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-3">
                <div wire:loading wire:target="verifyOtp"
                    class="absolute inset-0 bg-slate-900 dark:bg-white flex items-center justify-center z-10 transition-all rounded-2xl">
                    <svg class="animate-spin h-6 w-6 text-white dark:text-slate-900" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
                <span>{{ __('Verify Code') }}</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </div>

        <!-- Steps Progress -->
        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-3xl p-6 border border-slate-100 dark:border-slate-700/50">
            <div class="flex items-center justify-between mb-4">
                <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Step
                    2: Verification</span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">66% Complete</span>
            </div>
            <div class="h-2 w-full bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                <div
                    class="h-full w-2/3 bg-gradient-to-r from-indigo-600 to-purple-600 animate-pulse transition-all duration-1000">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                <span
                    class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter">Registered</span>
                <span
                    class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter">Verify
                    Email</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Launch AI</span>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col gap-4">
            <button wire:click="sendVerification" wire:loading.attr="disabled"
                class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-all duration-300 py-2">
                {{ __('Resend Verification Code') }}
            </button>

            <button wire:click="logout" type="button"
                class="text-sm font-bold text-slate-500 hover:text-red-500 dark:hover:text-red-400 transition-all duration-300 flex items-center justify-center gap-2 group py-2">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M17 16l4-4m0 0l-4-4m4 4H7" />
                </svg>
                <span>{{ __('Wrong email? Log Out') }}</span>
            </button>
        </div>

        <!-- Help Tip -->
        <div
            class="p-4 rounded-2xl bg-indigo-50/50 dark:bg-indigo-500/5 border border-indigo-100/50 dark:border-indigo-500/10">
            <p class="text-[11px] text-slate-500 dark:text-slate-400 text-center leading-relaxed">
                <span class="font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-tighter mr-1">Need
                    Help?</span>
                {{ __('If you haven\'t received the email within 2 minutes, check your spam or try resending.') }}
            </p>
        </div>
    </div>
</div>