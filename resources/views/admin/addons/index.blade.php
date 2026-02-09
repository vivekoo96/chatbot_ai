<x-admin-layout>
    <div class="py-10 space-y-12">
        <div class="flex items-center justify-between px-2">
            <div>
                <h2 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Manage Add-ons</h2>
                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mt-2">Scale your capacity with
                    specialized power-ups</p>
            </div>
            <a href="{{ route('admin.addons.create') }}"
                class="inline-flex items-center px-6 py-3 border border-transparent rounded-2xl shadow-xl text-xs font-black uppercase tracking-widest text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                New Add-on
            </a>
        </div>

        @if(session('success'))
            <div
                class="mx-2 bg-green-50 dark:bg-green-900/20 border-2 border-green-100 dark:border-green-800/30 text-green-800 dark:text-green-300 px-6 py-4 rounded-[1.5rem] text-sm font-bold flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Add-ons Grid -->
        <div class="px-2">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach($addons as $addon)
                    <div
                        class="group relative flex flex-col h-full p-6 bg-white dark:bg-slate-800 rounded-[2.5rem] border-2 border-slate-100 dark:border-slate-700 shadow-sm transition-all duration-300 hover:shadow-2xl">
                        <div class="flex justify-between items-start mb-8">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300 text-white">
                                    <x-icon name="{{ $addon->getIcon() }}" class="w-6 h-6" />
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-1 tracking-tight">
                                        {{ $addon->name }}</h3>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $addon->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ $addon->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.addons.edit', $addon) }}"
                                    class="p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.addons.destroy', $addon) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this add-on?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 rounded-xl bg-slate-50 dark:bg-slate-700/50 text-slate-400 hover:text-rose-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="space-y-6 mb-8">
                            <div class="flex justify-between items-baseline">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pricing</span>
                                <div class="text-right">
                                    <div class="text-lg font-black text-slate-900 dark:text-white">
                                        ₹{{ number_format($addon->price_inr) }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 tracking-tight">
                                        ${{ number_format($addon->price_usd, 2) }} / one-time</div>
                                </div>
                            </div>

                            <div class="pt-8 border-t border-slate-50 dark:border-slate-700/50 grid grid-cols-2 gap-6">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1.5">Type
                                    </p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white capitalize">
                                        {{ $addon->type }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-1.5">
                                        Quantity</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">
                                        {{ number_format($addon->quantity) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <div class="bg-slate-50 dark:bg-slate-700/50 rounded-2xl p-6 mb-6">
                                <p class="text-[10px] text-slate-400 uppercase font-black tracking-widest mb-2">Target
                                    Benefit</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed italic">
                                    {{ $addon->description ?? 'No specific description provided for this power-up.' }}
                                </p>
                            </div>
                            <a href="{{ route('admin.addons.edit', $addon) }}"
                                class="block w-full py-3 px-4 bg-slate-900 dark:white text-white dark:text-slate-900 rounded-xl font-bold text-xs text-center uppercase tracking-widest hover:bg-slate-800 transition-colors">
                                Modify Add-on
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-admin-layout>