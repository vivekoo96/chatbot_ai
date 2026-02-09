<x-admin-layout>
    <div class="py-10 space-y-12">
        <div class="flex items-center justify-between px-2">
            <div>
                <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Subscription Plans</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-2">Manage your service tiers and
                    pricing strategy</p>
            </div>
            <a href="{{ route('admin.plans.create') }}"
                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-indigo-500/25 transition-all duration-300 hover:scale-105 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Plan
            </a>
        </div>

        <!-- Plans Grid -->
        <div class="px-2">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($plans as $plan)
                    <div
                        class="group relative flex flex-col h-full p-6 bg-white dark:bg-slate-800 rounded-[2.5rem] border-2 {{ $plan->slug === 'pro' ? 'border-indigo-500 shadow-xl shadow-indigo-500/10' : 'border-slate-100 dark:border-slate-700 shadow-sm' }} transition-all duration-300 hover:shadow-2xl">
                        <div class="flex justify-between items-start mb-6">
                            <div
                                class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $plan->slug === 'free' ? 'from-slate-400 to-slate-600' : ($plan->slug === 'starter' ? 'from-blue-400 to-blue-600' : ($plan->slug === 'pro' ? 'from-indigo-500 to-purple-600' : 'from-purple-500 to-pink-600')) }} flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                @if($plan->svg_icon)
                                    <div class="w-6 h-6 flex items-center justify-center text-white">
                                        {!! $plan->svg_icon !!}
                                    </div>
                                @else
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                @endif
                            </div>
                            <a href="{{ route('admin.plans.edit', $plan) }}"
                                class="p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                        </div>

                        <div class="mb-4">
                            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">
                                {{ $plan->name }}
                            </h3>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $plan->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ $plan->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-baseline">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pricing</span>
                                <div class="text-right">
                                    <div class="text-lg font-black text-slate-900 dark:text-white">
                                        ₹{{ number_format($plan->price_inr) }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 tracking-tight">
                                        ${{ number_format($plan->price_usd, 2) }} / mo</div>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-slate-50 dark:border-slate-700/50">
                                <div class="flex justify-between text-sm mb-3">
                                    <span class="text-slate-500 font-medium">Chatbots</span>
                                    <span
                                        class="font-bold text-slate-900 dark:text-white">{{ $plan->max_chatbots == -1 ? 'Unlimited' : $plan->max_chatbots }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-500 font-medium">Messages</span>
                                    <span
                                        class="font-bold text-slate-900 dark:text-white">{{ $plan->max_messages_per_month == -1 ? 'Unlimited' : number_format($plan->max_messages_per_month) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto pt-4">
                            <a href="{{ route('admin.plans.edit', $plan) }}"
                                class="block w-full py-3 px-4 bg-slate-100 dark:bg-slate-700 rounded-xl font-bold text-slate-900 dark:text-white text-xs text-center uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
                                Configure Plan
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>