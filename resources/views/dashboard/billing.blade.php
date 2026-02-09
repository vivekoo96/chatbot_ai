<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Billing & Subscription') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Current Plan Overview --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $plan->name }} Plan</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                @if($subscription)
                                    {{ $subscription->status === 'active' ? '✅ Active' : '⚠️ ' . ucfirst($subscription->status) }}
                                @else
                                    Free Plan
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-indigo-600">
                                ₹{{ number_format($plan->price_inr, 0) }}
                            </div>
                            <div class="text-sm text-gray-500">per month</div>
                        </div>
                    </div>

                    @if($subscription && $subscription->next_billing_at)
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="font-semibold text-blue-900 dark:text-blue-100">Next Renewal:</span>
                                </div>
                                <span
                                    class="text-blue-900 dark:text-blue-100">{{ $subscription->next_billing_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="grid md:grid-cols-2 gap-4">
                        <a href="{{ route('pricing') }}"
                            class="block p-4 border-2 border-indigo-200 dark:border-indigo-800 rounded-lg hover:border-indigo-400 transition-all">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Want to upgrade?</div>
                            <div class="font-semibold text-indigo-600">View Plans →</div>
                        </a>
                        @if($subscription && $subscription->status === 'active' && !$subscription->cancel_at_period_end)
                            <form action="{{ route('subscription.cancel') }}" method="POST"
                                class="block p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-red-400 transition-all cursor-pointer"
                                onclick="if(confirm('Are you sure you want to cancel your subscription?')) this.submit();">
                                @csrf
                                <div class="text-sm text-gray-600 dark:text-gray-400">Need to cancel?</div>
                                <div class="font-semibold text-red-600">Cancel Subscription</div>
                            </form>
                        @elseif($subscription && ($subscription->status === 'cancelled' || $subscription->cancel_at_period_end))
                            <form action="{{ route('subscription.resume') }}" method="POST"
                                class="block p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg hover:border-indigo-400 transition-all cursor-pointer"
                                onclick="this.submit();">
                                @csrf
                                <div class="text-sm text-gray-600 dark:text-gray-400">Changed your mind?</div>
                                <div class="font-semibold text-indigo-600">Resume Subscription</div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Usage Limits --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Usage & Limits</h3>

                    {{-- Messages --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-semibold">💬 Messages</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ number_format($messagesUsed) }} /
                                {{ $messagesLimit == -1 ? '∞' : number_format($messagesLimit) }}
                                @if($messagesFromAddons > 0)
                                    <span class="text-green-600">+ {{ number_format($messagesFromAddons) }} add-on</span>
                                @endif
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            @php
                                $percentage = $messagesLimit > 0 ? min(($messagesUsed / $messagesLimit) * 100, 100) : 0;
                            @endphp
                            <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>

                    {{-- Chatbots --}}
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-semibold">🤖 Chatbots</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $user->chatbots->count() }} /
                                {{ $plan->max_chatbots == -1 ? '∞' : $plan->max_chatbots }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            @php
                                $chatbotPercentage = $plan->max_chatbots > 0 ? min(($user->chatbots->count() / $plan->max_chatbots) * 100, 100) : 0;
                            @endphp
                            <div class="bg-purple-600 h-3 rounded-full" style="width: {{ $chatbotPercentage }}%"></div>
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Need more capacity? <a href="{{ route('pricing') }}"
                                class="text-indigo-600 font-semibold hover:text-indigo-700">Purchase Add-ons →</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Active Add-ons --}}
            @if($activeAddons->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Active Add-ons</h3>

                        <div class="space-y-3">
                            @foreach($activeAddons as $userAddon)
                                <div class="border dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h4 class="font-semibold">{{ $userAddon->addon->getIcon() }}
                                                {{ $userAddon->addon->name }}
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ number_format($userAddon->quantity_remaining) }} /
                                                {{ number_format($userAddon->quantity_total) }} remaining
                                            </p>
                                        </div>
                                        <div class="text-right text-sm">
                                            <div class="text-gray-600 dark:text-gray-400">Expires</div>
                                            <div class="font-semibold">{{ $userAddon->expires_at->format('M d, Y') }}</div>
                                        </div>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full"
                                            style="width: {{ $userAddon->getRemainingPercentage() }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Payment History --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Payment History</h3>

                    @if($payments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Description</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Expires/Renews</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                            Invoice</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td class="px-4 py-3 text-sm">{{ $payment['date']->format('M d, Y') }}</td>
                                            <td class="px-4 py-3 text-sm font-medium">
                                                @if($payment['type'] === 'subscription')
                                                    🔄 {{ $payment['description'] }}
                                                @else
                                                    📦 {{ $payment['description'] }}
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm font-semibold">
                                                ₹{{ number_format($payment['amount'], 2) }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full
                                                                                    {{ $payment['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($payment['status']) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                @if(isset($payment['next_billing']))
                                                    {{ $payment['next_billing']->format('M d, Y') }}
                                                @elseif(isset($payment['expires_at']))
                                                    {{ $payment['expires_at']->format('M d, Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm">
                                                <a href="{{ route('billing.invoice.download', ['type' => $payment['type'], 'id' => $payment['id']]) }}"
                                                    class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    PDF
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>No payment history yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>