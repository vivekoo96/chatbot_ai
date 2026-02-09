<div class="py-10">
    <div class="px-4 sm:px-6 lg:px-8 max-w-full mx-auto">
        <!-- Header Section -->
        <div class="mb-10 animate-fade-in text-center lg:text-left">
            <div class="flex flex-col lg:flex-row items-center gap-4 mb-4">
                <div
                    class="w-16 h-16 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/5">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">System Configuration
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">Manage your ecosystem's core settings, API
                        keys, and identity.</p>
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mb-8 animate-fade-in">
                <div class="max-w-4xl mx-auto">
                    <div
                        class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 px-6 py-4 rounded-3xl flex items-center gap-4 shadow-xl shadow-emerald-500/5">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="font-bold">{{ session('message') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <div class="max-w-4xl mx-auto space-y-8 animate-fade-in" style="animation-delay: 0.1s">
            <!-- General Settings -->
            <div
                class="premium-card p-10 rounded-[3rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl overflow-hidden group">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-indigo-500 rounded-full"></span>
                    Identity & Brand
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="col-span-1">
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Website
                            Name</label>
                        <input type="text" wire:model.defer="site_name" class="premium-input w-full"
                            placeholder="e.g. ChatBoat AI">
                        @error('site_name') <span
                            class="text-rose-500 text-[10px] mt-2 ml-1 font-bold uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-span-1">
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Support
                            Email</label>
                        <input type="email" wire:model.defer="support_email" class="premium-input w-full"
                            placeholder="support@domain.com">
                        @error('support_email') <span
                            class="text-rose-500 text-[10px] mt-2 ml-1 font-bold uppercase">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- AI Engine Configuration -->
            <div
                class="premium-card p-10 rounded-[3rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl overflow-hidden group">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-purple-500 rounded-full"></span>
                    AI Engine (OpenAI)
                </h2>

                <div x-data="{ show: false }" class="relative">
                    <label
                        class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1 text-center">OpenAI
                        API Key</label>
                    <div class="relative group/input">
                        <input :type="show ? 'text' : 'password'" wire:model.defer="openai_key"
                            class="premium-input w-full text-center tracking-[0.5em] pr-12" placeholder="sk-...">
                        <button type="button" @click="show = !show"
                            class="absolute right-4 top-1/2 -translate-y-1/2 p-2 text-slate-400 hover:text-indigo-500 transition-colors">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.274 0 2.45.225 3.525.626M17.375 8.125A9.97 9.97 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7-1.274 0-2.45-.225-3.525-.626M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    @error('openai_key') <span
                        class="text-rose-500 text-[10px] mt-2 ml-1 font-bold uppercase block text-center">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Mail Configuration -->
            <div
                class="premium-card p-10 rounded-[3rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl overflow-hidden group">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                    SMTP Mail Configuration
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2">
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">SMTP
                            Host</label>
                        <input type="text" wire:model.defer="mail_host" class="premium-input w-full"
                            placeholder="mail.hemnix.com">
                    </div>
                    <div class="lg:col-span-1">
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Port</label>
                        <input type="number" wire:model.defer="mail_port" class="premium-input w-full"
                            placeholder="465">
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Username</label>
                        <input type="text" wire:model.defer="mail_username" class="premium-input w-full"
                            placeholder="chatbot@hemnix.com">
                    </div>
                    <div x-data="{ show: false }">
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model.defer="mail_password"
                                class="premium-input w-full pr-12" placeholder="********">
                            <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-500 transition-colors">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.274 0 2.45.225 3.525.626M17.375 8.125A9.97 9.97 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7-1.274 0-2.45-.225-3.525-.626M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Encryption</label>
                        <select wire:model.defer="mail_encryption" class="premium-input w-full">
                            <option value="">None</option>
                            <option value="ssl">SSL</option>
                            <option value="tls">TLS</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 lg:col-span-1">
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">From
                            Address</label>
                        <input type="email" wire:model.defer="mail_from_address" class="premium-input w-full"
                            placeholder="chatbot@hemnix.com">
                    </div>
                    <div class="md:col-span-2">
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">From
                            Name</label>
                        <input type="text" wire:model.defer="mail_from_name" class="premium-input w-full"
                            placeholder="HemnixBot">
                    </div>
                </div>
            </div>

            <!-- Payment Gateway -->
            <div
                class="premium-card p-10 rounded-[3rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl overflow-hidden group">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white flex items-center gap-3">
                        <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                        Razorpay Configuration
                    </h2>
                    <span
                        class="text-[10px] bg-amber-500/10 text-amber-600 px-3 py-1 rounded-full font-black uppercase tracking-widest border border-amber-500/20">Active
                        Gateway</span>
                </div>

                <div class="space-y-6">
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Razorpay
                            Key ID</label>
                        <input type="text" wire:model.defer="razorpay_key" class="premium-input w-full"
                            placeholder="rzp_test_...">
                    </div>
                    <div x-data="{ show: false }">
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Razorpay
                            Key Secret</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" wire:model.defer="razorpay_secret"
                                class="premium-input w-full pr-12" placeholder="********">
                            <button type="button" @click="show = !show"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-500 transition-colors">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7 1.274-4.057 5.064-7 9.542-7 1.274 0 2.45.225 3.525.626M17.375 8.125A9.97 9.97 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7-1.274 0-2.45-.225-3.525-.626M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    class="mt-8 p-4 rounded-2xl bg-amber-500/5 border border-amber-500/10 text-[10px] text-amber-600 font-bold uppercase tracking-wider text-center">
                    Note: Stripe options have been disabled as per your system configuration.
                </div>
            </div>

            <!-- Tax Configuration -->
            <div
                class="premium-card p-10 rounded-[3rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl overflow-hidden group">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-rose-500 rounded-full"></span>
                    Tax & GST Configuration
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="col-span-1">
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">GST
                            Percentage (%)</label>
                        <div class="relative group/input">
                            <input type="number" wire:model.defer="gst_percent" class="premium-input w-full"
                                placeholder="18">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-400 font-black">%</span>
                        </div>
                        @error('gst_percent') <span
                            class="text-rose-500 text-[10px] mt-2 ml-1 font-bold uppercase">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <p class="mt-6 text-xs text-slate-500 italic">This percentage will be added to all plan and add-on
                    purchases at checkout.</p>
            </div>

            <!-- Webhooks & Automation -->
            <div
                class="premium-card p-10 rounded-[3rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl overflow-hidden group">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-cyan-500 rounded-full"></span>
                    Automation & Webhooks
                </h2>

                <div class="space-y-6">
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Global
                            Webhook URL</label>
                        <input type="url" wire:model.defer="webhook_url" class="premium-input w-full"
                            placeholder="https://your-api.com/webhook">
                        @error('webhook_url') <span
                            class="text-rose-500 text-[10px] mt-2 ml-1 font-bold uppercase">{{ $message }}</span>
                        @enderror
                        <p class="mt-4 text-[10px] text-slate-500 font-medium leading-relaxed uppercase tracking-wider">
                            Enter a URL where the system will send real-time event notifications (Leads, Payments,
                            Support).
                        </p>
                    </div>
                </div>
            </div>

            <!-- Legal & Compliance -->
            <div
                class="premium-card p-10 rounded-[3rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl overflow-hidden group">
                <h2 class="text-2xl font-black text-slate-900 dark:text-white mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-amber-600 rounded-full"></span>
                    Legal & Compliance
                </h2>

                <div class="space-y-8">
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Privacy
                            Policy Content</label>
                        <textarea wire:model.defer="privacy_policy" rows="10"
                            class="premium-input w-full resize-none font-medium leading-relaxed"
                            placeholder="Enter your privacy policy text here..."></textarea>
                        @error('privacy_policy') <span
                            class="text-rose-500 text-[10px] mt-2 ml-1 font-bold uppercase">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Terms
                            & Conditions Content</label>
                        <textarea wire:model.defer="terms_conditions" rows="10"
                            class="premium-input w-full resize-none font-medium leading-relaxed"
                            placeholder="Enter your terms and conditions text here..."></textarea>
                        @error('terms_conditions') <span
                            class="text-rose-500 text-[10px] mt-2 ml-1 font-bold uppercase">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Save Action -->
            <div class="flex justify-center pt-8">
                <button wire:click="save" wire:loading.attr="disabled"
                    class="group px-16 py-6 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:scale-105 transition-all duration-500 text-white rounded-[2rem] font-black uppercase tracking-[0.3em] text-xs shadow-2xl shadow-indigo-500/40 flex items-center gap-4">
                    <span wire:loading.remove>Commit Configuration</span>
                    <span wire:loading>Syncing...</span>
                    <svg wire:loading.remove class="w-6 h-6 group-hover:rotate-12 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <style>
            .premium-input {
                @apply bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-800 rounded-2xl px-6 py-4 text-slate-900 dark:text-white font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder-slate-400 text-sm;
            }
        </style>
    </div>
</div>
</div>