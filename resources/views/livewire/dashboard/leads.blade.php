<div>
    <!-- Page Header -->
    <div class="max-w-7xl mx-auto mb-10">
        <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight leading-none mb-2">
            Leads
        </h1>
        <p class="text-slate-500 font-medium">Manage and view captured contact details from your chatbots</p>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto">
        <div
            class="premium-card rounded-[2rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/5 shadow-xl p-6">

            <!-- Search -->
            <div class="mb-6">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search leads by name or phone..."
                    class="w-full px-5 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-white/5 rounded-xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all outline-none font-medium text-sm">
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 dark:border-white/5">
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Phone</th>
                            <th class="px-4 py-3">Chatbot</th>
                            <th class="px-4 py-3">Captured</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($leads as $lead)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-4 text-sm font-bold text-slate-900 dark:text-white">
                                    {{ $lead->visitor_name ?? 'Unknown' }}
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-slate-600 dark:text-slate-300">
                                    {{ $lead->visitor_phone }}
                                </td>
                                <td class="px-4 py-4 text-xs font-bold text-indigo-600">
                                    {{ $lead->chatbot->name ?? 'Deleted Bot' }}
                                </td>
                                <td class="px-4 py-4 text-xs text-slate-400">
                                    {{ $lead->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <a href="{{ route('live-chat', ['conversationId' => $lead->id]) }}" wire:navigate
                                        class="text-xs font-bold text-indigo-600 hover:text-indigo-500 bg-indigo-50 dark:bg-indigo-500/10 px-3 py-1.5 rounded-lg border border-indigo-100 dark:border-indigo-500/20 transition-all hover:shadow-md hover:-translate-y-0.5 inline-block">
                                        View Chat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500 text-sm">
                                    No leads found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $leads->links() }}
            </div>
        </div>
    </div>
</div>