<div
    class="premium-card p-8 rounded-[2rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl">
    <div class="flex flex-col gap-6 mb-8">
        <div class="flex items-center justify-between">
            @php
                $statusColors = [
                    'active' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20',
                    'paused' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-500/20',
                    'cancelled' => 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-500/20',
                    'expired' => 'bg-slate-50 dark:bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-100 dark:border-slate-500/20',
                    'pending' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-100 dark:border-indigo-500/20',
                ];
                $currentStatus = $subscription_status ?? 'none';
                if ($currentStatus === 'active' && ($subscription->cancel_at_period_end ?? false)) {
                    $currentStatus = 'cancelling';
                    $statusColors['cancelling'] = 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-100 dark:border-amber-500/20';
                }
                $colorClass = $statusColors[$currentStatus] ?? 'bg-slate-50 dark:bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-100 dark:border-slate-500/20';
            @endphp
            <span
                class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $colorClass }}">
                {{ $currentStatus === 'cancelling' ? 'Cancelling Soon' : ($subscription_status ?: 'No Plan') }}
            </span>
            @if($plan && $plan->slug !== 'enterprise' && auth()->user()->isTeamAdmin())
                <a href="{{ route('pricing') }}"
                    class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Manage Plan
                </a>
            @endif
        </div>

        <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            {{ $plan?->name ?? 'No Plan' }}
        </h3>

        <div class="p-4 bg-slate-50 dark:bg-slate-950/50 rounded-2xl border border-slate-100 dark:border-slate-800/50">
            <p class="text-2xl font-black text-slate-900 dark:text-white">
                {{ $plan ? $plan->getFormattedPrice($currency) : 'Free' }}
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest ms-1">/ month</span>
            </p>
        </div>
    </div>

    <!-- Usage Statistics -->
    <div class="space-y-8">
        <!-- Chatbots Usage -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">AI
                    Assistants</span>
                <span class="text-sm font-black text-slate-900 dark:text-white">
                    {{ $active_chatbot_count }}/{{ $chatbot_count }} <span
                        class="text-[10px] text-slate-400 dark:text-slate-600 mx-1">Active</span>
                </span>
            </div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bot Capacity</span>
                <span class="text-sm font-black text-slate-900 dark:text-white">
                    {{ $chatbot_count }} <span class="text-slate-400 dark:text-slate-600 mx-1">/</span>
                    {{ $chatbot_limit == -1 ? '∞' : $chatbot_limit }}
                </span>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden p-0.5">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-1000"
                    style="width: {{ $chatbot_percentage }}%">
                </div>
            </div>
            @if($chatbot_percentage >= 80)
                <p class="text-[10px] font-bold text-amber-600 dark:text-amber-400 mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Approaching limit
                </p>
            @endif
        </div>

        <!-- Messages Usage -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Monthly
                    Messages</span>
                <span class="text-sm font-black text-slate-900 dark:text-white">
                    {{ number_format($messages_used) }} <span class="text-slate-400 dark:text-slate-600 mx-1">/</span>
                    {{ $messages_limit == -1 ? '∞' : number_format($messages_limit) }}
                </span>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden p-0.5">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2 rounded-full transition-all duration-1000"
                    style="width: {{ $messages_percentage }}%">
                </div>
            </div>
            @if($messages_percentage >= 80)
                <p class="text-[10px] font-bold text-amber-600 dark:text-amber-400 mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Approaching limit
                </p>
            @endif
        </div>

        <!-- Team Usage -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Team
                    Members</span>
                <span class="text-sm font-black text-slate-900 dark:text-white">
                    {{ number_format($team_count) }} <span class="text-slate-400 dark:text-slate-600 mx-1">/</span>
                    {{ $team_limit == -1 ? '∞' : number_format($team_limit) }}
                </span>
            </div>
            <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden p-0.5">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-1000"
                    style="width: {{ $team_percentage }}%">
                </div>
            </div>
            @if($team_percentage >= 80)
                <p class="text-[10px] font-bold text-amber-600 dark:text-amber-400 mt-2 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Approaching limit
                </p>
            @endif
        </div>
    </div>

    <!-- Upgrade CTA -->
    @if($plan && $plan->slug !== 'enterprise' && auth()->user()->isTeamAdmin())
        <div class="mt-10">
            <a href="{{ route('pricing') }}"
                class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-2xl shadow-xl shadow-indigo-500/20 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                Upgrade & Expand
            </a>
        </div>
    @endif

    <!-- Features List -->
    <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-800/50">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Included Features</p>
        <ul class="space-y-4">
            @foreach(($features ?? $plan->features ?? []) as $feature)
                <li class="flex items-start gap-3 group">
                    <div
                        class="mt-1 w-4 h-4 rounded-full bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span
                        class="text-sm font-semibold text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>