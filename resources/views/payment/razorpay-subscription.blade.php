<x-guest-layout title="Complete Your Subscription">
    <div class="min-h-screen py-20 flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-950 p-4">
        <!-- Progress Bar -->
        <div class="w-full max-w-md mb-8">
            <div class="flex justify-between mb-2">
                <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Step
                    3: Secure Payment</span>
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Final Step</span>
            </div>
            <div class="h-1.5 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                <div class="h-full w-full bg-gradient-to-r from-indigo-600 to-purple-600 animate-pulse"></div>
            </div>
        </div>

        <div class="w-full max-w-md">
            <div
                class="premium-card bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 rounded-[2.5rem] shadow-2xl p-8 md:p-10 relative overflow-hidden group">
                <!-- Decorative element -->
                <div
                    class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all duration-700">
                </div>

                <div class="relative z-10 text-center mb-8">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 bg-indigo-50 dark:bg-indigo-500/10 rounded-3xl mb-6 shadow-inner">
                        <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Finalize Plan</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-2 font-medium">Activate your
                        {{ $plan->name }} access
                    </p>
                </div>

                <!-- Plan Info -->
                <div
                    class="bg-slate-50 dark:bg-slate-800/50 rounded-3xl p-6 mb-8 border border-slate-100 dark:border-slate-700/50">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Plan selected</span>
                        <span
                            class="px-3 py-1 bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-indigo-500/20">{{ $plan->name }}</span>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-500 dark:text-slate-400">Base Price</span>
                            <span
                                class="font-bold text-slate-700 dark:text-slate-200">₹{{ number_format($plan->price_inr, 2) }}</span>
                        </div>

                        @if($plan->getGstAmount('INR') > 0)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-500 dark:text-slate-400">GST
                                    ({{ App\Models\Setting::getValue('gst_percent', 0) }}%)</span>
                                <span
                                    class="font-bold text-slate-700 dark:text-slate-200">+₹{{ number_format($plan->getGstAmount('INR'), 2) }}</span>
                            </div>
                        @endif

                        <div
                            class="pt-3 border-t border-slate-200 dark:border-slate-700 flex justify-between items-end">
                            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Total
                                Amount</span>
                            <div class="flex items-end gap-1 text-right">
                                <span
                                    class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">₹{{ number_format($plan->getTotalPriceWithGst('INR'), 2) }}</span>
                                <span
                                    class="text-slate-400 dark:text-slate-500 font-bold text-[10px] mb-1 leading-none">/
                                    mo</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="mt-4 pt-4 border-t border-slate-200/50 dark:border-slate-700/50 flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span
                            class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Includes
                            GST compliance</span>
                    </div>
                </div>

                <!-- Action Button -->
                <button id="rzp-button1"
                    class="group relative w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black py-5 rounded-2xl shadow-xl transition-all duration-300 hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-3">
                    <span id="button-text">Pay & Subscribe</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>

                <!-- Status Message -->
                <div id="payment-status" class="mt-4 text-center hidden">
                    <p class="text-[10px] font-bold uppercase tracking-widest"></p>
                </div>

                <!-- Branding & Trust -->
                <div
                    class="mt-10 flex items-center justify-center gap-6 opacity-40 grayscale contrast-125 transition-all hover:opacity-100 hover:grayscale-0">
                    <img src="https://razorpay.com/assets/razorpay-logo.svg" alt="Razorpay" class="h-4">
                    <div class="w-px h-4 bg-slate-300 dark:bg-slate-700"></div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-900 dark:text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span
                            class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-900 dark:text-white">Secure
                            Encrypted</span>
                    </div>
                </div>
            </div>

            <!-- Back link -->
            <div class="mt-8 text-center">
                <a href="{{ route('pricing') }}"
                    class="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-indigo-600 transition-colors">
                    &larr; Change Selection
                </a>
            </div>
        </div>
    </div>

    <!-- Razorpay Scripts -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const options = {
            "key": "{{ $key }}",
            "subscription_id": "{{ $razorpay_subscription->id }}",
            "name": "Chatboat AI",
            "description": "{{ $plan->name }} Plan Subscription",
            "image": "https://chatboat.ai/logo.png",
            "handler": function (response) {
                // Success logic
                updateStatus('Verification in progress...', 'indigo');

                fetch("{{ route('subscription.verify') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        razorpay_payment_id: response.razorpay_payment_id,
                        razorpay_subscription_id: response.razorpay_subscription_id,
                        razorpay_signature: response.razorpay_signature
                    })
                })
                    .then(res => {
                        if (!res.ok) {
                            return res.text().then(text => {
                                throw new Error('Server error: ' + res.status + ' ' + (text.substring(0, 100)));
                            });
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            updateStatus('Plan activated successfully!', 'emerald');
                            setTimeout(() => {
                                window.location.href = "{{ route('dashboard') }}?subscribed=true";
                            }, 1000);
                        } else {
                            updateStatus('Verification failed: ' + data.message, 'rose');
                        }
                    })
                    .catch(err => {
                        console.error('Verification error:', err);
                        updateStatus('Verification Error: ' + err.message, 'rose');
                    });
            },
            "prefill": {
                "name": "{{ $user->name }}",
                "email": "{{ $user->email }}"
            },
            "theme": {
                "color": "#4F46E5"
            },
            "modal": {
                "ondismiss": function () {
                    updateStatus('Subscription cancelled', 'rose');
                }
            }
        };

        const rzp1 = new Razorpay(options);

        document.getElementById('rzp-button1').onclick = function (e) {
            rzp1.open();
            e.preventDefault();
        }

        function updateStatus(message, color) {
            const statusDiv = document.getElementById('payment-status');
            const statusText = statusDiv.querySelector('p');
            statusText.textContent = message;
            statusText.className = `text-[10px] font-bold uppercase tracking-widest text-${color}-600 dark:text-${color}-400`;
            statusDiv.classList.remove('hidden');
        }
    </script>
</x-guest-layout>