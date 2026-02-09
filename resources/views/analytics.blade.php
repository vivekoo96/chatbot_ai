<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    Analytics Dashboard
                </h1>
                <p class="text-lg text-slate-500 dark:text-slate-400 mt-2 font-medium">
                    Track your chatbot performance and user engagement metrics
                </p>
            </div>

            <!-- Analytics Component -->
            @livewire('dashboard.analytics')
        </div>
    </div>
</x-app-layout>