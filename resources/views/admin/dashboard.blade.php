<x-admin-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-10 animate-fade-in">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                Global Administrator Hub
                            </span>
                        </div>
                        <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">System Overview
                        </h1>
                        <p class="text-lg text-slate-500 dark:text-slate-400 mt-2 font-medium">Monitoring global
                            platform performance and user engagement in real-time.</p>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
                <a href="{{ route('admin.users') }}"
                    class="premium-card p-8 rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl group hover:scale-[1.02] transition-all duration-300 block">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <span
                            class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">+{{ $stats['new_users_this_month'] ?? 0 }}
                            New</span>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Platform Users
                    </p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white leading-none">
                        {{ number_format($stats['total_users']) }}</p>
                </a>

                <a href="{{ route('admin.chatbots') }}"
                    class="premium-card p-8 rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl group hover:scale-[1.02] transition-all duration-300 block">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <span
                            class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">{{ $stats['active_chatbots'] ?? 0 }}
                            Online</span>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Global AI Assistants
                    </p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white leading-none">
                        {{ number_format($stats['total_chatbots']) }}</p>
                </a>

                <a href="{{ route('admin.dashboard') }}"
                    class="premium-card p-8 rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl group hover:scale-[1.02] transition-all duration-300 block">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center group-hover:bg-purple-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </div>
                        <span
                            class="text-[10px] font-black text-purple-500 uppercase tracking-widest">{{ $stats['today_conversations'] ?? 0 }}
                            Today</span>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Conversations
                    </p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white leading-none">
                        {{ number_format($stats['total_conversations']) }}</p>
                </a>

                <a href="{{ route('admin.payments') }}"
                    class="premium-card p-8 rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl group hover:scale-[1.02] transition-all duration-300 block">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-12 h-12 bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <span
                            class="text-[10px] font-black text-rose-500 uppercase tracking-widest">{{ $stats['avg_response_time'] ?? 'N/A' }}
                            Latency</span>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total System
                        Messages</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-white leading-none">
                        {{ number_format($stats['total_messages'] ?? 0) }}</p>
                </a>
            </div>

            <!-- Charts & Content Area -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                <!-- Main Growth Chart -->
                <div
                    class="lg:col-span-2 premium-card rounded-3xl p-8 bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Platform Growth</h3>
                        <div class="flex gap-2">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-indigo-500"></span>
                                <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">New
                                    Users</span>
                            </div>
                        </div>
                    </div>
                    <div class="h-[300px] relative">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>

                <!-- System Health Sidebar -->
                <div class="space-y-8">
                    <div
                        class="premium-card p-8 rounded-3xl bg-slate-900 text-white shadow-2xl relative overflow-hidden group">
                        <div
                            class="absolute -right-8 -bottom-8 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-150">
                        </div>
                        <div class="relative z-10">
                            <h4 class="text-xl font-black mb-6 uppercase tracking-widest text-slate-400 text-sm">System
                                Health</h4>
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-slate-300">Uptime</span>
                                    <span
                                        class="text-sm font-black text-emerald-400">{{ $stats['uptime'] ?? '99.9%' }}</span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-emerald-400 h-full w-[99.9%]"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-slate-300">API Load</span>
                                    <span
                                        class="text-sm font-black text-indigo-400">{{ $stats['api_calls_today'] ?? 0 }}
                                        req</span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-indigo-400 h-full w-[65%]"></div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold text-slate-300">Storage</span>
                                    <span
                                        class="text-sm font-black text-amber-400">{{ $stats['storage_usage'] ?? '12 MB' }}</span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-amber-400 h-full w-[42%]"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="premium-card p-8 rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl">
                        <h4 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-6">Daily Activity</h4>
                        <div class="h-[140px] relative">
                            <canvas id="conversationsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Enthusiasts -->
                <div
                    class="premium-card rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl overflow-hidden">
                    <div
                        class="p-8 border-b border-slate-100 dark:border-slate-800/50 flex items-center justify-between bg-slate-50/30 dark:bg-slate-800/20">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Recent Adopters</h3>
                        <a href="{{ route('admin.users') }}"
                            class="text-xs font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:underline">View
                            All</a>
                    </div>
                    <div class="p-0">
                        @foreach($recent_users as $user)
                            <div
                                class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800/50 last:border-0 hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-lg">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white leading-none mb-1">
                                            {{ $user->name }}</p>
                                        <p class="text-xs text-slate-500 font-medium">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        {{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Star Assistants -->
                <div
                    class="premium-card rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl overflow-hidden">
                    <div
                        class="p-8 border-b border-slate-100 dark:border-slate-800/50 flex items-center justify-between bg-slate-50/30 dark:bg-slate-800/20">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Flagship Assistants</h3>
                        <a href="{{ route('admin.chatbots') }}"
                            class="text-xs font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 hover:underline">Monitor
                            All</a>
                    </div>
                    <div>
                        @foreach($top_chatbots as $bot)
                            <div
                                class="flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800/50 last:border-0 hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900 dark:text-white leading-none mb-1">
                                            {{ $bot->name }}</p>
                                        <p class="text-xs text-slate-500 font-medium truncate max-w-[150px]">Owner:
                                            {{ $bot->user->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-black text-indigo-600 dark:text-indigo-400 leading-none">
                                        {{ number_format($bot->conversations_count) }}</p>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Sessions
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Configure Chart.js defaults
        Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
        Chart.defaults.color = '#94a3b8';

        // Users Growth Chart
        const usersCtx = document.getElementById('usersChart');
        const usersGradient = usersCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        usersGradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
        usersGradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        new Chart(usersCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart_data['user_labels']) !!},
                datasets: [{
                    label: 'New Adopters',
                    data: {!! json_encode($chart_data['user_data']) !!},
                    borderColor: '#4f46e5',
                    backgroundColor: usersGradient,
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#4f46e5',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 16,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 14, weight: 'black' },
                        cornerRadius: 12
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.1)', drawBorder: false },
                        ticks: { font: { weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 'bold' } }
                    }
                }
            }
        });

        // Conversations Activity Chart
        const conversationsCtx = document.getElementById('conversationsChart');
        new Chart(conversationsCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chart_data['conversation_labels']) !!},
                datasets: [{
                    data: {!! json_encode($chart_data['conversation_data']) !!},
                    backgroundColor: '#4f46e5',
                    borderRadius: 6,
                    maxBarThickness: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { display: false },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 'bold', size: 10 } }
                    }
                }
            }
        });
    </script>
</x-admin-layout>