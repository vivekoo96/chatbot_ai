<div>
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-sm">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header with Search -->
    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Active Accounts</h3>
        <div class="relative w-full sm:w-auto">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" wire:model.live="search" placeholder="Search accounts..."
                class="w-full sm:w-64 bg-slate-50 border-slate-200 rounded-xl text-xs font-bold pl-10 pr-4 py-2.5 placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all dark:bg-slate-800 dark:border-slate-700 dark:text-white">
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block">
        <div class="overflow-x-auto border border-slate-200 dark:border-slate-800 rounded-2xl">
            <table class="w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50/50 dark:bg-slate-800/50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-400">
                            User Profile
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-400">
                            Email
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-400">
                            Plan
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-400">
                            Bots
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-400">
                            Domains
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-[10px] font-black uppercase tracking-widest text-slate-400">
                            Role
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-right text-[10px] font-black uppercase tracking-widest text-slate-400">
                            Control
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-indigo-500/5 transition-all">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-indigo-500/20">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="font-bold text-slate-900 dark:text-white text-sm">
                                        {{ $user->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 max-w-[200px] truncate"
                                title="{{ $user->email }}">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->plan)
                                                    <span
                                                        class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg 
                                                                                        {{ $user->plan->slug === 'enterprise' ? 'bg-purple-500/10 text-purple-600' :
                                    ($user->plan->slug === 'pro' ? 'bg-indigo-500/10 text-indigo-600' :
                                        ($user->plan->slug === 'starter' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-100 text-slate-600')) }}">
                                                        {{ $user->plan->name }}
                                                    </span>
                                @else
                                    <span
                                        class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-rose-500/10 text-rose-500">
                                        No Plan
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700 dark:text-slate-300">
                                {{ $user->chatbots_count }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-[200px]">
                                    @php
                                        $domains = $user->chatbots->flatMap(function ($bot) {
                                            $allowed = is_array($bot->allowed_domains) ? $bot->allowed_domains : [];
                                            $detected = $bot->detected_website_url ? [parse_url($bot->detected_website_url, PHP_URL_HOST) ?: $bot->detected_website_url] : [];
                                            return array_merge($allowed, $detected);
                                        })->filter()->unique();
                                    @endphp

                                    @forelse($domains->take(3) as $domain)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 truncate max-w-[120px]"
                                            title="{{ $domain }}">
                                            {{ $domain }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-slate-400 italic">No domains</span>
                                    @endforelse

                                    @if($domains->count() > 3)
                                        <span class="text-[10px] text-slate-400 font-bold">+{{ $domains->count() - 3 }}
                                            more</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg 
                                            {{ $user->is_super_admin ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                                    {{ $user->is_super_admin ? 'Super Admin' : 'Customer' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                @if($user->id !== auth()->id())
                                    <button wire:click="impersonate({{ $user->id }})"
                                        class="text-[10px] font-black uppercase tracking-widest text-[var(--bot-primary)] hover:opacity-80 transition-all">
                                        Login as
                                    </button>
                                @endif
                                <button wire:click="deleteUser({{ $user->id }})"
                                    wire:confirm="Permanent deletion: Are you sure?"
                                    class="text-[10px] font-black uppercase tracking-widest text-rose-500 hover:text-rose-700 transition-colors">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-slate-400 font-medium">No results found for your query</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-4">
        @forelse($users as $user)
            <div
                class="bg-white dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-800/50 p-5 shadow-xl shadow-slate-200/50 dark:shadow-none">
                <div class="flex items-center gap-4 mb-5 pb-5 border-b border-slate-100 dark:border-slate-800/50">
                    <div
                        class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-500/20">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold text-slate-900 dark:text-white truncate">{{ $user->name }}</div>
                        <div class="text-[10px] font-bold text-slate-400 break-all uppercase tracking-wider mt-0.5">
                            {{ $user->email }}
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Current Plan</span>
                        @if($user->plan)
                                    <span
                                        class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg 
                                                                {{ $user->plan->slug === 'enterprise' ? 'bg-purple-500/10 text-purple-600' :
                            ($user->plan->slug === 'pro' ? 'bg-indigo-500/10 text-indigo-600' :
                                ($user->plan->slug === 'starter' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-slate-100 text-slate-600')) }}">
                                        {{ $user->plan->name }}
                                    </span>
                        @else
                            <span
                                class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg bg-rose-500/10 text-rose-500">
                                No Plan
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Deployments</span>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-900 dark:text-white block">{{ $user->chatbots_count }}
                                Chatbots</span>
                            @php
                                $mobileDomains = $user->chatbots->flatMap(function ($bot) {
                                    $allowed = is_array($bot->allowed_domains) ? $bot->allowed_domains : [];
                                    $detected = $bot->detected_website_url ? [parse_url($bot->detected_website_url, PHP_URL_HOST) ?: $bot->detected_website_url] : [];
                                    return array_merge($allowed, $detected);
                                })->filter()->unique();
                            @endphp
                            @if($mobileDomains->isNotEmpty())
                                <div class="text-[10px] text-slate-500 mt-1 max-w-[150px] truncate">
                                    {{ $mobileDomains->implode(', ') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Access Level</span>
                        <span
                            class="px-2.5 py-1 text-[10px] font-black uppercase tracking-widest rounded-lg 
                                    {{ $user->is_super_admin ? 'bg-indigo-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                            {{ $user->is_super_admin ? 'Super Admin' : 'Customer' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-6 pt-5 border-t border-slate-100 dark:border-slate-800/50">
                    @if($user->id !== auth()->id())
                        <button wire:click="impersonate({{ $user->id }})"
                            class="flex items-center justify-center px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 text-[var(--bot-primary)] text-[10px] font-black uppercase tracking-widest hover:bg-[var(--bot-primary)] hover:text-white transition-all">
                            Login as
                        </button>
                    @endif
                    <button wire:click="deleteUser({{ $user->id }})" wire:confirm="Permanent deletion: Are you sure?"
                        class="flex items-center justify-center px-4 py-3 rounded-xl bg-rose-500/10 text-rose-500 text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div
                class="text-center py-12 bg-white dark:bg-slate-900/40 rounded-2xl border border-slate-200 dark:border-slate-800/50">
                <p class="text-slate-400 font-medium">No results found</p>
            </div>
        @endforelse
    </div>
</div>