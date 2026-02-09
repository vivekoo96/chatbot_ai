<div class="space-y-6">
    <!-- Period Selector -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <h2 class="text-2xl font-black text-slate-900 dark:text-white">Analytics Dashboard</h2>
        <div class="flex gap-2 flex-wrap">
            <button wire:click="$set('period', '7days')"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $period === '7days' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                7 Days
            </button>
            <button wire:click="$set('period', '30days')"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $period === '30days' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                30 Days
            </button>
            <button wire:click="$set('period', '90days')"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ $period === '90days' ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-700' }}">
                90 Days
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
            class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total
                        Conversations</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white">
                        {{ number_format($totalConversations) }}
                    </p>
                </div>
            </div>
        </div>

        <div
            class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-green-500/10 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total
                        Messages</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white">{{ number_format($totalMessages) }}
                    </p>
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
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Avg.
                        Messages/Chat</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white">{{ $avgMessagesPerConversation }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{
        conversationTrend: @js($conversationTrend),
        messageTrend: @js($messageTrend),
        conversationChart: null,
        messageChart: null,
        initCharts() {
            this.$nextTick(() => {
                const conversationData = this.conversationTrend;
                const messageData = this.messageTrend;
                
                // Conversation Chart
                const convCtx = document.getElementById('conversationChart');
                if (!convCtx) return;
                
                if (this.conversationChart) this.conversationChart.destroy();
                this.conversationChart = new Chart(convCtx, {
                    type: 'line',
                    data: {
                        labels: conversationData.map(d => {
                            const date = new Date(d.date);
                            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        }),
                        datasets: [{
                            label: 'Conversations',
                            data: conversationData.map(d => d.count),
                            borderColor: 'rgb(147, 51, 234)',
                            backgroundColor: 'rgba(147, 51, 234, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: { 
                            y: { 
                                beginAtZero: true,
                                ticks: { stepSize: 1 }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });

                // Message Chart
                const msgCtx = document.getElementById('messageChart');
                if (!msgCtx) return;

                if (this.messageChart) this.messageChart.destroy();
                this.messageChart = new Chart(msgCtx, {
                    type: 'bar',
                    data: {
                        labels: messageData.map(d => {
                            const date = new Date(d.date);
                            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                        }),
                        datasets: [{
                            label: 'Messages',
                            data: messageData.map(d => d.count),
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: { 
                            y: { 
                                beginAtZero: true,
                                ticks: { stepSize: 1 }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            });
        }
    }" x-init="initCharts()">
        <!-- Conversation Trend Chart -->
        <div
            class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Conversation Trend</h3>
            <div class="h-[200px] relative">
                <canvas id="conversationChart"></canvas>
            </div>
        </div>

        <!-- Message Volume Chart -->
        <div
            class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Message Volume</h3>
            <div class="h-[200px] relative">
                <canvas id="messageChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chatbot Performance Table -->
    <div
        class="premium-card p-6 rounded-2xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Chatbot Performance</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-slate-700">
                        <th
                            class="text-left py-3 px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Chatbot Name</th>
                        <th
                            class="text-right py-3 px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Conversations</th>
                        <th
                            class="text-right py-3 px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Messages</th>
                        <th
                            class="text-right py-3 px-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Avg. Messages</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($chatbotStats as $stat)
                        <tr
                            class="border-b border-gray-100 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-3 px-4 font-semibold text-slate-900 dark:text-white">{{ $stat->name }}</td>
                            <td class="py-3 px-4 text-right text-slate-700 dark:text-slate-300">
                                {{ number_format($stat->conversation_count) }}
                            </td>
                            <td class="py-3 px-4 text-right text-slate-700 dark:text-slate-300">
                                {{ number_format($stat->message_count) }}
                            </td>
                            <td class="py-3 px-4 text-right text-slate-700 dark:text-slate-300">
                                {{ $stat->conversation_count > 0 ? round($stat->message_count / $stat->conversation_count, 1) : 0 }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                No data available for this period
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>