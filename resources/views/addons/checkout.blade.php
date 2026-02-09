<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }} - {{ $addon->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Add-on Details --}}
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-2xl font-bold">{{ $addon->getIcon() }} {{ $addon->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $addon->description }}</p>
                            </div>
                            <div class="text-right space-y-2">
                                <div class="text-sm text-gray-500 font-medium">Subtotal:
                                    {{ $addon->getFormattedPrice($currency) }}</div>
                                @if($addon->getGstAmount($currency) > 0)
                                    <div class="text-sm text-gray-500 font-medium">
                                        GST ({{ \App\Models\Setting::getValue('gst_percent', 0) }}%):
                                        {{ $currency === 'INR' ? '₹' : '$' }}{{ number_format($addon->getGstAmount($currency), 2) }}
                                    </div>
                                @endif
                                <div class="text-3xl font-black text-indigo-600">
                                    {{ $currency === 'INR' ? '₹' : '$' }}{{ number_format($addon->getTotalPriceWithGst($currency), 2) }}
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">
                                    {{ $addon->getPerUnitCost($currency) }}</div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="font-semibold mb-2">What you'll get:</h4>
                            <ul class="space-y-2">
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ number_format($addon->quantity) }} {{ ucfirst($addon->type) }}
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Available immediately after purchase
                                </li>
                                <li class="flex items-center text-sm">
                                    <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Expires at end of billing cycle
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Payment Form --}}
                    <form id="payment-form" action="{{ route('addons.verify') }}" method="POST">
                        @csrf
                        <input type="hidden" name="addon_id" value="{{ $addon->id }}">
                        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="{{ $order_id }}">
                        <input type="hidden" name="razorpay_signature" id="razorpay_signature">

                        <button type="button" id="rzp-button"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 px-6 rounded-2xl transition-all shadow-xl shadow-indigo-500/20 active:scale-[0.98] flex items-center justify-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Pay {{ $currency === 'INR' ? '₹' : '$' }}{{ number_format($addon->getTotalPriceWithGst($currency), 2) }}
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <a href="{{ route('pricing') }}"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600">
                            ← Back to Pricing
                        </a>
                    </div>

                    {{-- Security Notice --}}
                    <div
                        class="mt-8 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                            <div class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>Secure Payment:</strong> Your payment is processed securely through Razorpay. We
                                never store your card details.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Razorpay Script --}}
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        document.getElementById('rzp-button').onclick = function (e) {
            e.preventDefault();

            var options = {
                "key": "{{ $key }}",
                "amount": "{{ $amount * 100 }}",
                "currency": "{{ $currency }}",
                "name": "{{ config('app.name') }}",
                "description": "{{ $addon->name }}",
                "order_id": "{{ $order_id }}",
                "handler": function (response) {
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                    document.getElementById('razorpay_signature').value = response.razorpay_signature;
                    document.getElementById('payment-form').submit();
                },
                "prefill": {
                    "name": "{{ $user->name }}",
                    "email": "{{ $user->email }}"
                },
                "theme": {
                    "color": "#4F46E5"
                }
            };

            var rzp = new Razorpay(options);
            rzp.open();
        };
    </script>
</x-app-layout>