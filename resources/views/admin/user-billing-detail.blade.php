<x-admin-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Header Section -->
            <div class="animate-fade-in">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-6">
                        <a href="{{ route('admin.user-billing') }}"
                            class="w-12 h-12 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl flex items-center justify-center text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all shadow-sm hover:shadow-md group">
                            <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                        </a>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                    Customer Account
                                </span>
                            </div>
                            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                                {{ $user->name }}
                            </h1>
                            <p class="text-slate-500 dark:text-slate-400 font-medium">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Info & Current Plan --}}
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">User Information</h3>
                    <div class="space-y-2 text-sm">
                        <div><span class="font-semibold">Name:</span> {{ $user->name }}</div>
                        <div><span class="font-semibold">Email:</span> {{ $user->email }}</div>
                        <div><span class="font-semibold">Joined:</span> {{ $user->created_at->format('M d, Y') }}</div>
                        <div><span class="font-semibold">Country:</span> {{ $user->country ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Current Plan</h3>
                    <div class="space-y-2">
                        @if($user->plan)
                            <div class="text-2xl font-black text-indigo-600">{{ $user->plan->name }}</div>
                            <div class="text-sm font-bold text-slate-500 dark:text-slate-400">
                                ₹{{ number_format((float) ($user->plan->price_inr ?? 0), 0) }} / month
                            </div>
                            @if($user->activeSubscription && $user->activeSubscription->next_billing_at)
                                <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="text-sm font-semibold text-green-800 dark:text-green-200">
                                        Next Billing: {{ $user->activeSubscription->next_billing_at->format('M d, Y') }}
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-lg text-gray-500">No plan assigned</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Usage Stats --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Usage Statistics</h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">💬 Messages</span>
                            <span class="text-sm text-gray-600">
                                {{ number_format($messagesUsed) }} /
                                {{ $messagesLimit == -1 ? '∞' : number_format($messagesLimit) }}
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            @php
                                $msgPercentage = $messagesLimit > 0 ? min(($messagesUsed / $messagesLimit) * 100, 100) : 0;
                            @endphp
                            <div class="bg-indigo-600 h-3 rounded-full" style="width: {{ $msgPercentage }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold">🤖 Chatbots</span>
                            <span class="text-sm text-gray-600">
                                {{ $activeChatbots }}/{{ $totalChatbots }} Active
                            </span>
                        </div>
                        @php
                            $botPercentage = $chatbotsLimit > 0 ? min(($totalChatbots / $chatbotsLimit) * 100, 100) : 0;
                        @endphp
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="bg-purple-600 h-3 rounded-full" style="width: {{ $botPercentage }}%"></div>
                        </div>
                        <div class="mt-1 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                            Limit: {{ $chatbotsLimit == -1 ? '∞' : $chatbotsLimit }} Total Bots
                        </div>
                    </div>
                </div>
            </div>

            {{-- Active Add-ons --}}
            @if($activeAddons->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Active Add-ons ({{ $activeAddons->count() }})</h3>

                    <div class="space-y-3">
                        @foreach($activeAddons as $userAddon)
                            <div class="border dark:border-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h4 class="font-semibold">{{ $userAddon->addon->getIcon() }}
                                            {{ $userAddon->addon->name }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Purchased: {{ $userAddon->purchased_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <div class="text-right text-sm">
                                        <div class="font-semibold">{{ number_format($userAddon->quantity_remaining) }} /
                                            {{ number_format($userAddon->quantity_total) }}
                                        </div>
                                        <div class="text-red-600">Expires: {{ $userAddon->expires_at->format('M d, Y') }}</div>
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
            @endif

            {{-- Subscription History --}}
            @if($subscriptions->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Subscription History</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Next Billing
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($subscriptions as $subscription)
                                    <tr>
                                        <td class="px-4 py-2 text-sm">{{ $subscription->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-2 text-sm font-semibold">{{ $subscription->plan->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">₹{{ number_format((float) $subscription->amount, 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full
                                                                                {{ $subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($subscription->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ $subscription->next_billing_at ? $subscription->next_billing_at->format('M d, Y') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Expired Add-ons --}}
            @if($expiredAddons->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4">Expired Add-ons</h3>

                    <div class="space-y-2">
                        @foreach($expiredAddons as $userAddon)
                            <div
                                class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg opacity-60">
                                <div>
                                    <span class="font-semibold">{{ $userAddon->addon->name }}</span>
                                    <span class="text-sm text-gray-500 ml-2">
                                        ({{ $userAddon->purchased_at->format('M d, Y') }})
                                    </span>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    {{ ucfirst($userAddon->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>