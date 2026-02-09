<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Set New Password</h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Choose a strong password to secure your account.
        </p>
    </div>

    <form wire:submit="resetPassword" class="space-y-6">
        <!-- Email Address -->
        <div class="space-y-1.5">
            <x-input-label for="email" :value="__('Email Address')"
                class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider" />
            <x-text-input wire:model="email" id="email"
                class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5"
                type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1.5">
            <x-input-label for="password" :value="__('New Password')"
                class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider" />
            <x-text-input wire:model="password" id="password"
                class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5"
                type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="space-y-1.5">
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')"
                class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation"
                class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5"
                type="password" name="password_confirmation" required autocomplete="new-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="flex flex-col gap-4 mt-8 pt-6 border-t border-slate-100 dark:border-slate-800/50">
            <button type="submit"
                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold px-10 py-4 rounded-2xl shadow-xl shadow-indigo-500/25 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                <span>Reset Password</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </button>
        </div>
    </form>
</div>