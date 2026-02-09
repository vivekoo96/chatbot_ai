<x-admin-layout>
    <div class="py-10">
        <div class="px-4 sm:px-6 lg:px-8 max-w-full mx-auto">
            <!-- Header Section -->
            <div class="mb-10 animate-fade-in">
                <div class="flex items-center gap-4 mb-4">
                    <div
                        class="w-12 h-12 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">User Registry</h1>
                        <p class="text-slate-500 dark:text-slate-400 font-medium">Managing all registered accounts
                            across the ChatBoat AI ecosystem.</p>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div
                    class="premium-card p-6 rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Scale</p>
                    <p class="text-2xl font-black text-slate-900 dark:text-white">{{ number_format($total_users) }}
                        Users</p>
                </div>
                <div
                    class="premium-card p-6 rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Privileged</p>
                    <p class="text-2xl font-black text-indigo-500">{{ number_format($super_admins) }} Admins</p>
                </div>
                <div
                    class="premium-card p-6 rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Standard</p>
                    <p class="text-2xl font-black text-emerald-500">{{ number_format($regular_users) }} Regulars</p>
                </div>
                <div
                    class="premium-card p-6 rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Momentum</p>
                    <p class="text-2xl font-black text-amber-500">+{{ $new_this_month }} Monthly</p>
                </div>
            </div>

            <!-- Interactive Table -->
            <div
                class="premium-card rounded-3xl bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl overflow-hidden animate-fade-in">
                <div class="p-4 sm:p-8">
                    @livewire('admin.user-management')
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>