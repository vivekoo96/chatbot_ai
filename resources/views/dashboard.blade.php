<x-app-layout>
    <div class="py-6">
        <!-- Personalized Welcome Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-400 mb-2">
                    Workspace Overview</p>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-none">
                    {{ match (true) { now()->hour < 12 => 'Good morning', now()->hour < 18 => 'Good afternoon', default => 'Good evening'} }},
                    <span
                        class="text-indigo-600 dark:text-indigo-400">{{ explode(' ', auth()->user()->name)[0] }}</span>
                </h1>
            </div>
            <div class="flex items-center gap-3 px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400">AI
                    Cluster Online</span>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-12">
            <!-- Metric Card -->
            <a href="#analytics"
                class="premium-card p-6 rounded-[2rem] transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 group block">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total
                            Conversations</p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white leading-none">
                            {{ number_format(auth()->user()->is_super_admin ? \App\Models\Conversation::count() : auth()->user()->chatbots()->withCount('conversations')->get()->sum('conversations_count')) }}
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-indigo-500/10 text-indigo-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span
                        class="text-[10px] font-bold text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-full">+12.5%</span>
                    <span class="text-[10px] text-slate-400 font-medium">vs last month</span>
                </div>
            </a>

            <a href="#chatbot-manager"
                class="premium-card p-6 rounded-[2rem] transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 group block">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Agent Capacity
                        </p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white leading-none">
                            {{ auth()->user()->is_super_admin ? \App\Models\Chatbot::where('is_active', true)->count() : auth()->user()->chatbots()->where('is_active', true)->count() }}/{{ auth()->user()->is_super_admin ? \App\Models\Chatbot::count() : auth()->user()->chatbots()->count() }}
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-emerald-500/10 text-emerald-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span
                        class="text-[10px] font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full">Optimal</span>
                </div>
            </a>

            <a href="#analytics"
                class="premium-card p-6 rounded-[2rem] transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 group block">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Resolution Rate
                        </p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white leading-none">94.2%</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-purple-500/10 text-purple-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span
                        class="text-[10px] font-bold text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-full">+2.4%</span>
                </div>
            </a>

            <a href="#analytics"
                class="premium-card p-6 rounded-[2rem] transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 group block">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Avg Response</p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white leading-none">
                            < 1.2s</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-rose-500/10 text-rose-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span
                        class="text-[10px] font-bold text-blue-500 bg-blue-500/10 px-2 py-0.5 rounded-full">Real-time</span>
                </div>
            </a>
        </div>

        <!-- Full Width Workspace -->
        <div class="space-y-8">
            @livewire('dashboard.chatbot-manager')

            <!-- Secondary Analytics -->
            <div id="analytics" class="scroll-mt-24">
                @livewire('dashboard.analytics')
            </div>

            <!-- Bottom Row: Plan Usage & CTA -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @livewire('dashboard.plan-usage')

                <!-- Premium Specialist CTA -->
                <div
                    class="premium-card p-1 bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-700 rounded-[2.5rem] shadow-2xl overflow-hidden group">
                    <div class="bg-slate-900/90 backdrop-blur-xl p-8 rounded-[2.4rem] relative overflow-hidden">
                        <div
                            class="absolute -right-12 -bottom-12 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700">
                        </div>
                        <div class="relative z-10">
                            <div
                                class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6 text-white border border-white/10 group-hover:rotate-6 transition-all">
                                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h4 class="text-xl font-black text-white mb-2">Unlock Growth</h4>
                            <p class="text-sm text-slate-400 mb-8 leading-relaxed">Optimization strategies available
                                24/7. Connect with an expert for a deep dive.</p>
                            <a href="{{ route('contact') }}"
                                class="flex items-center justify-center gap-2 w-full py-4 bg-white text-slate-900 font-bold rounded-2xl text-sm hover:bg-slate-100 transition-all active:scale-[0.98]">
                                Contact Specialist
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>