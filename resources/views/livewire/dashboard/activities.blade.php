<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-8 px-4 sm:px-6 lg:px-8" wire:poll.15s="$refresh">
    <!-- Page Header -->
    <div class="max-w-7xl mx-auto mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-none mb-2">
                    Activity Timeline
                </h1>
                <p class="text-slate-500 font-medium">Real-time monitoring of all chatbot interactions and events</p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Auto Refresh Toggle -->
                <button wire:click="toggleAutoRefresh"
                    class="px-5 py-3 rounded-xl font-bold text-xs uppercase tracking-widest transition-all {{ $autoRefresh ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 {{ $autoRefresh ? 'animate-spin' : '' }}" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span>{{ $autoRefresh ? 'Live' : 'Paused' }}</span>
                    </div>
                </button>

                <!-- Export Button -->
                <button wire:click="exportActivities"
                    class="px-5 py-3 bg-indigo-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/30">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Export CSV</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto mb-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Activities -->
            <div
                class="premium-card rounded-[2rem] p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white mb-1">{{ number_format($stats['total']) }}
                </p>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Events</p>
            </div>

            <!-- Today's Activities -->
            <div
                class="premium-card rounded-[2rem] p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white mb-1">{{ number_format($stats['today']) }}
                </p>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Today's Activity</p>
            </div>

            <!-- Messages Today -->
            <div
                class="premium-card rounded-[2rem] p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white mb-1">
                    {{ number_format($stats['messages']) }}
                </p>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Messages Today</p>
            </div>

            <!-- Total Leads -->
            <div
                class="premium-card rounded-[2rem] p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-600/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-white mb-1">{{ number_format($stats['leads']) }}
                </p>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Leads Captured</p>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div
            class="premium-card rounded-[2rem] p-6 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 shadow-xl">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Search -->
                <div class="lg:col-span-4">
                    <label
                        class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-3">Search</label>
                    <input type="text" wire:model.debounce.500ms="search" placeholder="Visitor, chatbot, or message..."
                        class="w-full px-5 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-white/5 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-medium text-sm">
                </div>

                <!-- Event Type Filter -->
                <div class="lg:col-span-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-3">Event
                        Type</label>
                    <select wire:model="eventTypeFilter"
                        class="w-full px-5 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-white/5 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-bold text-sm">
                        <option value="all">All Events</option>
                        <option value="chat_started">Chat Started</option>
                        <option value="message_sent">Message Sent</option>
                        <option value="lead_captured">Lead Captured</option>
                    </select>
                </div>

                <!-- Date From -->
                <div class="lg:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-3">From
                        Date</label>
                    <input type="date" wire:model="dateFrom"
                        class="w-full px-5 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-white/5 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-medium text-sm">
                </div>

                <!-- Date To -->
                <div class="lg:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-3">To
                        Date</label>
                    <input type="date" wire:model="dateTo"
                        class="w-full px-5 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-white/5 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-medium text-sm">
                </div>

                <!-- Clear Filters -->
                <div class="lg:col-span-1 flex items-end">
                    <button wire:click="clearFilters"
                        class="w-full px-4 py-3 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-300 dark:hover:bg-slate-700 transition-all">
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="max-w-7xl mx-auto">
        <div
            class="premium-card rounded-[2.5rem] overflow-hidden bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 shadow-2xl">
            <div class="p-8 space-y-4">
                @forelse($activities as $activity)
                    <div
                        class="group flex items-start gap-6 p-6 bg-slate-50 dark:bg-slate-950/50 rounded-[1.5rem] border border-slate-100 dark:border-white/5 hover:border-indigo-600/30 transition-all">
                        <!-- Avatar -->
                        <div
                            class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-black text-sm shrink-0 shadow-lg
                                    {{ $activity->type === 'session_started' ? 'bg-emerald-600' : ($activity->type === 'message_sent' ? 'bg-indigo-600' : 'bg-amber-600') }}">
                            {{ strtoupper(substr($activity->message ?? 'A', 0, 2)) }}
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4 mb-2">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h3 class="text-lg font-black text-slate-900 dark:text-white">
                                        {{ ucfirst(str_replace('_', ' ', $activity->type)) }}
                                    </h3>
                                    <span
                                        class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                {{ $activity->type === 'session_started' ? 'bg-emerald-600/10 text-emerald-600' : ($activity->type === 'message_sent' ? 'bg-indigo-600/10 text-indigo-600' : 'bg-amber-600/10 text-amber-600') }}">
                                        {{ str_replace('_', ' ', $activity->type) }}
                                    </span>
                                    @if($activity->chatbot)
                                        <span
                                            class="px-3 py-1 bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-[10px] font-bold">
                                            {{ $activity->chatbot->name }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 shrink-0">
                                    {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>

                            @if($activity->message)
                                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mt-3 italic">
                                    "{{ Str::limit($activity->message, 150) }}"
                                </p>
                            @endif

                            <div
                                class="flex items-center gap-2 mt-3 text-[9px] text-slate-400 font-bold uppercase tracking-widest">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $activity->created_at->format('M d, Y • h:i A') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-20 text-center">
                        <div
                            class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">No Activities Found</h3>
                        <p class="text-slate-500 font-medium">Try adjusting your filters or wait for new events to occur.
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($activities->hasPages())
                <div class="px-8 py-6 border-t border-slate-100 dark:border-white/5">
                    {{ $activities->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .premium-card {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .premium-card:hover {
            transform: translateY(-2px);
        }
    </style>
</div>