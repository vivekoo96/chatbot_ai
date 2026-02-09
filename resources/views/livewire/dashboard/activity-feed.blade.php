<div wire:poll.15s>
    <div
        class="premium-card p-8 rounded-[2rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl overflow-hidden relative">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h4 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-1">Live Activity</h4>
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-slate-500">System Pulse</span>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            @forelse($activities as $activity)
                    <div class="flex gap-4 group animate-fade-in">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300 {{ 
                                    match ($activity->type) {
                    'message_sent' => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400',
                    'session_started' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                    'lead_captured' => 'bg-purple-500/10 text-purple-600 dark:text-purple-400',
                    default => 'bg-slate-100 text-slate-500'
                }
                                }} group-hover:scale-110">
                                @if($activity->type === 'message_sent')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                @elseif($activity->type === 'session_started')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </div>
                            <div class="w-px h-full bg-slate-100 dark:bg-slate-800 my-2 group-last:hidden"></div>
                        </div>
                        <div class="flex-1 pb-6 group-last:pb-0">
                            <div class="flex justify-between items-start mb-1">
                                <h5 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">
                                    {{ $activity->message }}</h5>
                                <span
                                    class="text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap ml-4">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">
                                Assistant: <span
                                    class="font-bold text-indigo-500">{{ $activity->chatbot->name ?? 'System' }}</span>
                            </p>
                        </div>
                    </div>
            @empty
                <div class="py-12 text-center">
                    <div
                        class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 opacity-50">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No activity yet</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800/50">
            <a href="{{ route('activities') }}" wire:navigate
                class="block w-full text-center py-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-all flex items-center justify-center gap-2">
                <span>View Full Activity Log</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>