<x-app-layout>
    <div class="py-6">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-400 mb-2">
                    Collaboration Workspace</p>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-none">
                    Team <span class="text-indigo-600 dark:text-indigo-400">Management</span>
                </h1>
            </div>
        </div>

        @livewire('dashboard.team-management')
    </div>
</x-app-layout>