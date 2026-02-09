<x-admin-layout>
    <div class="py-10">
        <div class="px-4 sm:px-6 lg:px-8 max-w-full mx-auto">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    Payment Analytics
                </h1>
                <p class="text-lg text-slate-500 dark:text-slate-400 mt-2 font-medium">
                    Monitor revenue, subscriptions, and transaction history
                </p>
            </div>

            <!-- Revenue Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div
                    class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-green-500/10 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total
                                Revenue</p>
                            <p class="text-3xl font-black text-slate-900 dark:text-white">
                                ₹{{ number_format($total_revenue, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">This
                                Month</p>
                            <p class="text-3xl font-black text-slate-900 dark:text-white">
                                ₹{{ number_format($monthly_revenue, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Active Subscriptions</p>
                            <p class="text-3xl font-black text-slate-900 dark:text-white">
                                {{ number_format($total_subscriptions) }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-orange-500/10 text-orange-600 dark:text-orange-400 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">New
                                This Month</p>
                            <p class="text-3xl font-black text-slate-900 dark:text-white">
                                {{ number_format($new_subscriptions_this_month) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscriptions by Plan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div
                    class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Subscriptions by Plan</h3>
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @forelse($subscriptions_by_plan as $plan)
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-800/50 rounded-xl">
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-white">{{ $plan->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $plan->count }} subscribers</p>
                                </div>
                                <p class="text-xl font-bold text-purple-600 dark:text-purple-400">
                                    ₹{{ number_format($plan->revenue, 2) }}</p>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400 py-8">No subscription data available</p>
                        @endforelse
                    </div>
                </div>

                <div
                    class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Revenue Trend</h3>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div
                class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Recent Transactions</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-slate-700">
                                <th
                                    class="text-left py-3 px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    User</th>
                                <th
                                    class="text-left py-3 px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Plan</th>
                                <th
                                    class="text-left py-3 px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Status</th>
                                <th
                                    class="text-left py-3 px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Gateway</th>
                                <th
                                    class="text-right py-3 px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Amount</th>
                                <th
                                    class="text-right py-3 px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_transactions as $transaction)
                                <tr
                                    class="border-b border-gray-100 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="py-3 px-4">
                                        <div>
                                            <p class="font-semibold text-slate-900 dark:text-white">
                                                {{ $transaction->user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $transaction->user->email }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $transaction->plan->name }}</td>
                                    <td class="py-3 px-4">
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-bold {{ $transaction->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700 dark:text-slate-300">
                                        {{ ucfirst($transaction->gateway) }}</td>
                                    <td class="py-3 px-4 text-right font-bold text-slate-900 dark:text-white">
                                        ₹{{ number_format($transaction->plan->price_inr, 2) }}</td>
                                    <td class="py-3 px-4 text-right text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->created_at->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                        No transactions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const revenueData = @json($revenue_trend);
        const ctx = document.getElementById('revenueChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: revenueData.map(d => d.month),
                datasets: [{
                    label: 'Subscriptions',
                    data: revenueData.map(d => d.count),
                    borderColor: 'rgb(147, 51, 234)',
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</x-admin-layout>