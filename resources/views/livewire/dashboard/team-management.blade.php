<div class="space-y-8">
    @if(auth()->user()->isTeamAdmin())
        <!-- Invitation Form -->
        <div
            class="premium-card p-8 rounded-[2rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl">
            <div class="flex items-center gap-4 mb-8">
                <div class="p-3 bg-indigo-500/10 text-indigo-600 rounded-2xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">Invite Team Member</h3>
                    <p class="text-sm text-slate-500 font-medium">Add a new collaborator to your workspace</p>
                </div>
            </div>

            @if (session()->has('message'))
                <div
                    class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 rounded-2xl text-sm font-bold">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-600 rounded-2xl text-sm font-bold">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="invite" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Full
                        Name</label>
                    <input type="text" wire:model="name"
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all"
                        placeholder="Enter name">
                    @error('name') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Email
                        Address</label>
                    <input type="email" wire:model="email"
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all"
                        placeholder="email@example.com">
                    @error('email') <span class="text-rose-500 text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Role</label>
                    <select wire:model="role" @if(!$canAssignRoles) disabled @endif
                        class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all disabled:opacity-50">
                        <option value="member">Member</option>
                        @if($canAssignRoles)
                            <option value="admin">Admin</option>
                            <option value="viewer">Viewer</option>
                        @endif
                    </select>
                    @if(!$canAssignRoles)
                        <p class="text-[10px] text-indigo-500 font-bold mt-2">Business Plan Required for roles</p>
                    @endif
                </div>

                <div class="md:col-span-1 flex items-end">
                    <button type="submit" @if($reachedLimit) disabled @endif
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-xl shadow-indigo-500/20 transition-all active:scale-[0.98] disabled:opacity-50 disabled:grayscale">
                        {{ $reachedLimit ? 'Limit Reached' : 'Add Member' }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Team Members List -->
    <div
        class="premium-card rounded-[2rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl overflow-hidden">
        <div
            class="p-8 border-b border-slate-100 dark:border-slate-800/50 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white">Active Members</h3>
                <p class="text-sm text-slate-500 font-medium">{{ $currentCount }} of
                    {{ $maxMembers == -1 ? 'unlimited' : $maxMembers }} seats used
                </p>
            </div>
            <div class="relative max-w-xs w-full">
                <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" wire:model.live="search"
                    class="w-full pl-12 pr-5 py-3 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 transition-all"
                    placeholder="Search members...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr
                        class="bg-slate-50 dark:bg-slate-950/30 text-left border-b border-slate-100 dark:border-slate-800/50">
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                            Collaborator</th>
                        <th
                            class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 border-l border-slate-100 dark:border-slate-800/50">
                            Role</th>
                        @if(auth()->user()->isTeamAdmin())
                            <th
                                class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                    @forelse($members as $member)
                        <tr class="group hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold">
                                        {{ strtoupper(substr($member->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-900 dark:text-white">{{ $member->name }}</p>
                                        <p class="text-[10px] font-bold text-slate-400">{{ $member->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @if($canAssignRoles)
                                    <select wire:change="updateRole({{ $member->id }}, $event.target.value)"
                                        class="px-3 py-1.5 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-none rounded-lg text-xs font-black uppercase tracking-widest focus:ring-2 focus:ring-indigo-500 transition-all">
                                        <option value="member" {{ $member->role === 'member' ? 'selected' : '' }}>Member</option>
                                        <option value="admin" {{ $member->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="viewer" {{ $member->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                                    </select>
                                @else
                                    <span
                                        class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-black uppercase tracking-widest border border-slate-200 dark:border-slate-700">
                                        {{ $member->role }}
                                    </span>
                                @endif
                            </td>
                            @if(auth()->user()->isTeamAdmin())
                                <td class="px-8 py-6 text-right">
                                    <button wire:click="removeMember({{ $member->id }})"
                                        wire:confirm="Are you sure you want to remove this member?"
                                        class="p-2 text-rose-500 hover:bg-rose-500/10 rounded-xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isTeamAdmin() ? 3 : 2 }}" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="p-4 bg-slate-100 dark:bg-slate-800 rounded-full text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-500">No team members found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>