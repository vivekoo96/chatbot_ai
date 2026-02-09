<x-admin-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-10 animate-fade-in">
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-12 h-12 bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Bot Directory</h1>
                        <p class="text-slate-500 dark:text-slate-400 font-medium">Monitoring the health and activity of
                            every AI assistant on the platform.</p>
                    </div>
                </div>
            </div>

            <!-- Bot Management Table -->
            <div
                class="premium-card rounded-[2rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl overflow-hidden animate-fade-in">
                <div
                    class="p-8 border-b border-slate-100 dark:border-slate-800/50 flex items-center justify-between bg-slate-50/30 dark:bg-slate-800/20">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Active Deployment</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800/50">
                        <thead class="bg-slate-50/50 dark:bg-slate-800/30">
                            <tr>
                                <th
                                    class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Assistant Name</th>
                                <th
                                    class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Deployment Owner</th>
                                <th
                                    class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Session Count</th>
                                <th
                                    class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Operational Status</th>
                                <th
                                    class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Deployment Domains</th>
                                <th
                                    class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    User Plan</th>
                                <th
                                    class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                                    Access Token</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                            @foreach($chatbots as $bot)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </div>
                                            <span
                                                class="text-sm font-black text-slate-900 dark:text-white">{{ $bot->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                            {{ $bot->user->name }}
                                        </div>
                                        <div class="text-[10px] font-medium text-slate-500">{{ $bot->user->email }}</div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span
                                            class="text-lg font-black text-indigo-600 dark:text-indigo-400">{{ number_format($bot->conversations_count) }}</span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        @if($bot->is_active)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-600 border border-emerald-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-500/10 text-slate-500 border border-slate-500/20">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-500 mr-2"></span>
                                                Disabled
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-sm font-medium">
                                        @if(!empty($bot->allowed_domains))
                                            <div class="flex flex-wrap gap-1 max-w-[200px]">
                                                @foreach($bot->allowed_domains as $domain)
                                                    <span
                                                        class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md text-[10px] text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                                        {{ $domain }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span
                                                class="text-[10px] font-bold text-amber-500 uppercase tracking-wider italic">Open
                                                (Global)</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div
                                            class="px-3 py-1 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-lg text-xs font-black inline-flex items-center gap-2 border border-indigo-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            {{ $bot->user->plan->name ?? 'No Plan' }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <code
                                                class="text-[10px] font-black px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg text-slate-500 border border-slate-200 dark:border-slate-700">
                                                        {{ Str::limit($bot->token, 12) }}
                                                    </code>
                                            <button class="text-slate-400 hover:text-indigo-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>