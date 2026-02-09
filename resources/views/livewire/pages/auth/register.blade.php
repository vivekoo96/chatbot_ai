<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $showPassword = false;
    public bool $showConfirmPassword = false;

    // New Fields
    public string $business_name = '';
    public string $industry = '';
    public string $country = '';
    public string $business_size = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'business_name' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string'],
            'country' => ['required', 'string'],
            'business_size' => ['required', 'string'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Create your account</h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
            Start building your AI assistant in minutes. No credit card required.
        </p>
    </div>

    <form wire:submit="register" class="space-y-8">
        <!-- Section: Personal -->
        <div class="space-y-5">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-indigo-500 rounded-full shadow-[0_0_10px_rgba(79,70,229,0.5)]"></div>
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Personal
                    Information</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="name" :value="__('Full Name')"
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1" />
                    <x-text-input wire:model="name" id="name"
                        class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5"
                        type="text" name="name" required autofocus placeholder="John Doe" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Work Email')"
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1" />
                    <x-text-input wire:model="email" id="email"
                        class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5"
                        type="email" name="email" required placeholder="john@company.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>
            </div>
        </div>

        <!-- Section: Business -->
        <div class="space-y-5 pt-4">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-purple-500 rounded-full shadow-[0_0_10px_rgba(168,85,247,0.5)]"></div>
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Business
                    Profile</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="business_name" :value="__('Company Name')"
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1" />
                    <x-text-input wire:model="business_name" id="business_name"
                        class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5"
                        type="text" required placeholder="Acme Inc." />
                    <x-input-error :messages="$errors->get('business_name')" class="mt-1.5" />
                </div>

                <div>
                    <x-input-label for="country" :value="__('Country')"
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1" />
                    <select wire:model="country" id="country"
                        class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-2xl py-3.5 px-5 shadow-sm transition-all duration-300 outline-none">
                        <option value="">Select Country</option>
                        <option value="IN">India 🇮🇳</option>
                        <option value="US">United States 🇺🇸</option>
                        <option value="UK">United Kingdom 🇬🇧</option>
                        <option value="AE">UAE 🇦🇪</option>
                        <option value="AU">Australia 🇦🇺</option>
                        <option value="DE">Germany 🇩🇪</option>
                        <option value="FR">France 🇫🇷</option>
                        <option value="CA">Canada 🇨🇦</option>
                        <option value="OTHER">Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('country')" class="mt-1.5" />
                </div>

                <div>
                    <x-input-label for="industry" :value="__('Industry')"
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1" />
                    <select wire:model="industry" id="industry"
                        class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-2xl py-3.5 px-5 shadow-sm transition-all duration-300 outline-none">
                        <option value="">Select Industry</option>
                        <option value="tech">Technology / SaaS</option>
                        <option value="ecommerce">E-commerce</option>
                        <option value="realestate">Real Estate</option>
                        <option value="healthcare">Healthcare</option>
                        <option value="education">Education</option>
                        <option value="finance">Finance</option>
                        <option value="services">Professional Services</option>
                        <option value="other">Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('industry')" class="mt-1.5" />
                </div>

                <div>
                    <x-input-label for="business_size" :value="__('Team Size')"
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1" />
                    <select wire:model="business_size" id="business_size"
                        class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 rounded-2xl py-3.5 px-5 shadow-sm transition-all duration-300 outline-none">
                        <option value="">Select Size</option>
                        <option value="solopreneur">Solopreneur (1)</option>
                        <option value="startup">Startup (2-10)</option>
                        <option value="smb">SMB (11-50)</option>
                        <option value="mid">Mid-Market (51-200)</option>
                        <option value="enterprise">Enterprise (201+)</option>
                    </select>
                    <x-input-error :messages="$errors->get('business_size')" class="mt-1.5" />
                </div>
            </div>
        </div>

        <!-- Section: Security -->
        <div class="space-y-5 pt-4">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-emerald-500 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Security
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="password" :value="__('Password')"
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1" />
                    <div class="relative w-full">
                        <x-text-input wire:model="password" id="password"
                            class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5 pr-12"
                            :type="$showPassword ? 'text' : 'password'" required autocomplete="new-password"
                            placeholder="••••••••" />
                        <button type="button" wire:click="$toggle('showPassword')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
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
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                        class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1" />
                    <div class="relative w-full">
                        <x-text-input wire:model="password_confirmation" id="password_confirmation"
                            class="block w-full bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700/50 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 py-3.5 px-5 pr-12"
                            :type="$showConfirmPassword ? 'text' : 'password'" required autocomplete="new-password"
                            placeholder="••••••••" />
                        <button type="button" wire:click="$toggle('showConfirmPassword')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                            @if($showConfirmPassword)
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
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
                </div>
            </div>
        </div>

        <div
            class="flex flex-col sm:flex-row items-center justify-between gap-6 mt-10 pt-8 border-t border-slate-100 dark:border-slate-800/50">
            <a class="text-sm font-semibold text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all duration-300 flex items-center gap-2 group"
                href="{{ route('login') }}" wire:navigate>
                <span>Already have an account?</span>
                <span class="text-indigo-600 dark:text-indigo-400 group-hover:underline">Log in</span>
            </a>

            <button type="submit"
                class="w-full sm:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold px-10 py-4 rounded-2xl shadow-xl shadow-indigo-500/25 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2">
                <span>Launch My AI Assistant</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </button>
        </div>
    </form>
</div>