<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-400 mb-2">Service
            Desk</p>
        <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Support Inquiries</h1>
    </div>

    <!-- Filters -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input wire:model.live="search" type="text"
                class="w-full pl-11 pr-4 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-sm"
                placeholder="Search inquiries...">
        </div>

        <select wire:model.live="status"
            class="px-4 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-sm">
            <option value="">All Statuses</option>
            <option value="unread">Unread</option>
            <option value="read">Read</option>
            <option value="replied">Replied</option>
        </select>
    </div>

    <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Sender</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Subject</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Status</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Received</th>
                        <th
                            class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-900 leading-tight">{{ $msg->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $msg->email }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col max-w-xs">
                                    <span class="text-sm font-bold text-slate-700 truncate">{{ $msg->subject }}</span>
                                    <span class="text-xs text-slate-400 truncate">{{ $msg->message }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if($msg->status === 'unread')
                                    <span
                                        class="px-3 py-1 bg-amber-500/10 text-amber-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Unread</span>
                                @elseif($msg->status === 'read')
                                    <span
                                        class="px-3 py-1 bg-indigo-500/10 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Read</span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-emerald-500/10 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Replied</span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <span
                                    class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ $msg->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div
                                    class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if($msg->status === 'unread')
                                        <button wire:click="markAsRead({{ $msg->id }})"
                                            class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Mark as Read">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    @endif
                                    <button wire:click="delete({{ $msg->id }})" wire:confirm="Remove this inquiry?"
                                        class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors"
                                        title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div
                                    class="w-16 h-16 bg-slate-50 text-slate-300 rounded-[2rem] flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2-2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V15a2 2 0 012-2m16 0h-4m-8 0H4">
                                        </path>
                                    </svg>
                                </div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">No support
                                    inquiries
                                    found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
            {{ $messages->links() }}
        </div>
    </div>
</div>