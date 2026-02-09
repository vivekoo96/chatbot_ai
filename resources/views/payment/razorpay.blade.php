<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-2xl font-bold text-center mb-6 text-gray-900 dark:text-white">Complete Payment</h2>

            <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <p class="text-sm text-gray-600 dark:text-gray-300">Plan</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $plan->name }}</p>
                <p class="text-xl font-bold text-indigo-600 mt-2">₹{{ number_format($plan->price_inr, 2) }}</p>
            </div>

            <!-- Status Messages -->
            <div id="status-message" class="hidden mb-4 p-3 rounded-lg"></div>

            <!-- Razorpay Button -->
            <button id="rzp-button1"
                class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Pay Now with Razorpay
            </button>

            <form action="{{ route('payment.razorpay.verify') }}" method="POST" id="verify-form">
                @csrf
                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
                <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
            </form>
        </div>
    </div>

    <!-- Razorpay Scripts -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        console.log('=== Razorpay Payment Page Loaded ===');
        console.log('Order ID:', '{{ $order_id }}');
        console.log('Plan:', '{{ $plan->name }}', '(ID: {{ $plan->id }})');
        console.log('Amount:', '{{ $plan->price_inr * 100 }}', 'paise');

        function showStatus(message, type = 'info') {
            const statusDiv = document.getElementById('status-message');
            statusDiv.className = `mb-4 p-3 rounded-lg ${type === 'error' ? 'bg-red-100 text-red-700' : type === 'success' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'}`;
            statusDiv.textContent = message;
            statusDiv.classList.remove('hidden');
        }

        var options = {
            "key": "{{ $key }}",
            "amount": "{{ $plan->price_inr * 100 }}",
            "currency": "INR",
            "name": "ChatBoat AI",
            "description": "Subscription for {{ $plan->name }} Plan",
            "image": "{{ asset('images/logo.png') }}",
            "order_id": "{{ $order_id }}",
            "handler": function (response) {
                console.log('=== Payment Successful ===');
                console.log('Payment ID:', response.razorpay_payment_id);
                console.log('Order ID:', response.razorpay_order_id);
                console.log('Signature:', response.razorpay_signature);

                showStatus('Payment successful! Verifying...', 'success');

                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value = response.razorpay_signature;

                console.log('Submitting verification form to:', '{{ route('payment.razorpay.verify') }}');
                document.getElementById('verify-form').submit();
            },
            "prefill": {
                "name": "{{ $user->name }}",
                "email": "{{ $user->email }}",
                "contact": ""
            },
            "theme": {
                "color": "#4F46E5"
            },
            "modal": {
                "ondismiss": function () {
                    console.log('=== Payment Modal Dismissed ===');
                    showStatus('Payment cancelled', 'error');
                }
            }
        };

        var rzp1 = new Razorpay(options);

        rzp1.on('payment.failed', function (response) {
            console.error('=== Payment Failed ===');
            console.error('Error Code:', response.error.code);
            console.error('Error Description:', response.error.description);
            console.error('Error Source:', response.error.source);
            console.error('Error Step:', response.error.step);
            console.error('Error Reason:', response.error.reason);

            showStatus('Payment failed: ' + response.error.description, 'error');
        });

        document.getElementById('rzp-button1').onclick = function (e) {
            console.log('=== Opening Razorpay Modal ===');
            showStatus('Opening payment gateway...', 'info');
            rzp1.open();
            e.preventDefault();
        }
    </script>
</x-guest-layout>