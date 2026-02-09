<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Profile Settings</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Manage your account preferences and
                    security.</p>
            </div>

            <div
                class="premium-card p-8 rounded-[2rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div
                class="premium-card p-8 rounded-[2rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-xl">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div
                class="premium-card p-8 rounded-[2rem] border border-rose-500/10 bg-rose-50/10 dark:bg-rose-500/5 shadow-xl">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>