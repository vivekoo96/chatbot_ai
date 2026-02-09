<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="relative z-50">
    <!-- Desktop Sidebar -->
    <div
        class="hidden lg:flex flex-col w-72 h-screen fixed left-0 top-0 bg-slate-900 border-r border-slate-800 shadow-2xl transition-all duration-300">
        <!-- Logo Area -->
        <div class="p-8">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-4 group">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20 group-hover:rotate-12 transition-transform duration-500">
                    <x-application-logo class="w-7 h-7" />
                </div>
                <div>
                    <span class="block text-xl font-black text-white tracking-tight">{{ config('app.name') }}</span>
                    <span
                        class="block text-[10px] font-black uppercase tracking-[0.3em] text-indigo-400 leading-none">Control
                        Center</span>
                </div>
            </a>
        </div>

        @if(auth()->user()->invited_by_user_id)
            <div class="px-6 pb-2">
                <div class="flex items-center gap-2 px-3 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-xl">
                    <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400">Team Member Mode</span>
                </div>
            </div>
        @endif

        <!-- Navigation Links -->
        <div class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
            <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-4 opacity-50">Main Menu
            </p>

            @if(auth()->user()->is_super_admin)
                <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" wire:navigate
                    class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('admin.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm font-bold">{{ __('Platform Control') }}</span>
                </x-nav-link>
            @endif

            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate
                class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                <span class="text-sm font-bold">{{ __('Assistants') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('live-chat')" :active="request()->routeIs('live-chat')" wire:navigate
                class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('live-chat') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-sm font-bold">{{ __('Live Chat') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('leads')" :active="request()->routeIs('leads')" wire:navigate
                class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('leads') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="text-sm font-bold">{{ __('Leads') }}</span>
            </x-nav-link>

            @if(auth()->user()->getOwner()->plan && auth()->user()->getOwner()->plan->has_team_access)
                <x-nav-link :href="route('team')" :active="request()->routeIs('team')" wire:navigate
                    class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('team') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm font-bold">{{ __('Team') }}</span>
                </x-nav-link>
            @endif

            <x-nav-link :href="route('analytics')" :active="request()->routeIs('analytics')" wire:navigate
                class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('analytics') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-sm font-bold">{{ __('Analytics') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('activities')" :active="request()->routeIs('activities')" wire:navigate
                class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('activities') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <span class="text-sm font-bold">{{ __('Activity Log') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('pricing')" :active="request()->routeIs('pricing')" wire:navigate
                class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('pricing') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-bold">{{ __('Plans') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('billing')" :active="request()->routeIs('billing')" wire:navigate
                class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('billing') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <span class="text-sm font-bold">{{ __('Billing') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('docs')" :active="request()->routeIs('docs')" wire:navigate
                class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ request()->routeIs('docs') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="text-sm font-bold">{{ __('Documentation') }}</span>
            </x-nav-link>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 space-y-4">
            <!-- Plan Info -->
            <div class="p-4 bg-indigo-600/10 rounded-3xl border border-indigo-500/20">
                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-400 mb-1">
                    @if(auth()->user()->invited_by_user_id)
                        Managed By {{ auth()->user()->getOwner()->name }}
                    @else
                        Active Plan
                    @endif
                </p>
                <div class="flex items-center justify-between">
                    <span
                        class="text-sm font-bold text-white">{{ auth()->user()->getOwner()->plan->name ?? 'Free Plan' }}</span>
                    @if(!auth()->user()->invited_by_user_id)
                        <a href="{{ route('pricing') }}"
                            class="text-[10px] font-bold text-indigo-400 hover:text-indigo-300">Upgrade</a>
                    @endif
                </div>
            </div>

            <!-- Profile Dropdown (Sidebar) -->
            <x-dropdown align="top" width="w-full"
                contentClasses="py-2 bg-white dark:bg-slate-800 shadow-2xl border border-slate-200 dark:border-slate-700">
                <x-slot name="trigger">
                    <button type="button"
                        class="w-full flex items-center gap-3 p-3 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all duration-300">
                        <div
                            class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center text-white font-bold shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="text-left min-w-0">
                            <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-slate-500 truncate">Settings & Profile</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-500 ml-auto" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile')" wire:navigate
                        class="font-bold py-3 flex items-center gap-2">
                        <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        {{ __('My Profile') }}
                    </x-dropdown-link>
                    <div class="border-t border-slate-100 dark:border-slate-800"></div>
                    <button wire:click="logout" class="w-full text-start">
                        <x-dropdown-link
                            class="font-bold py-3 text-rose-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            {{ __('Sign Out') }}
                        </x-dropdown-link>
                    </button>
                </x-slot>
            </x-dropdown>
        </div>
    </div>

    <!-- Mobile Navigation Bar -->
    <div
        class="lg:hidden flex items-center justify-between h-16 px-6 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 fixed top-0 w-full z-40">
        <div class="flex items-center gap-2">
            <div
                class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white">
                <x-application-logo class="w-5 h-5" />
            </div>
            <span class="text-lg font-black dark:text-white">{{ config('app.name') }}</span>
        </div>
        <button @click="open = ! open" class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Drawer -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full" class="fixed inset-0 z-50 lg:hidden">

        <div class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm" @click="open = false"></div>

        <div class="relative w-72 h-full bg-slate-900 shadow-2xl flex flex-col">
            <!-- Sidebar Content matches desktop for consistency -->
            <div class="p-8 pb-4">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                    <x-application-logo class="w-7 h-7" />
                </div>
            </div>
            @if(auth()->user()->invited_by_user_id)
                <div class="px-8 pb-4">
                    <div class="flex items-center gap-2 px-3 py-2 bg-indigo-500/10 border border-indigo-500/20 rounded-xl">
                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-indigo-400">Team Member
                            Mode</span>
                    </div>
                </div>
            @endif
            <div class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                @if(auth()->user()->is_super_admin)
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')"
                        wire:navigate class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Admin Console</span>
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                    wire:navigate class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span>Assistants</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('live-chat')" :active="request()->routeIs('live-chat')"
                    wire:navigate class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Live Chat</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('leads')" :active="request()->routeIs('leads')" wire:navigate
                    class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Leads</span>
                </x-responsive-nav-link>
                @if(auth()->user()->getOwner()->plan && auth()->user()->getOwner()->plan->has_team_access)
                    <x-responsive-nav-link :href="route('team')" :active="request()->routeIs('team')" wire:navigate
                        class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Team</span>
                    </x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('analytics')" :active="request()->routeIs('analytics')"
                    wire:navigate class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Analytics</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('activities')" :active="request()->routeIs('activities')"
                    wire:navigate class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Activity Log</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pricing')" :active="request()->routeIs('pricing')" wire:navigate
                    class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Plans</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('billing')" :active="request()->routeIs('billing')" wire:navigate
                    class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span>Billing</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('docs')" :active="request()->routeIs('docs')" wire:navigate
                    class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Documentation</span>
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile')" :active="request()->routeIs('profile')" wire:navigate
                    class="px-4 py-3 rounded-xl font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profile</span>
                </x-responsive-nav-link>
            </div>

            <div class="p-6 border-t border-slate-800">
                <button wire:click="logout"
                    class="w-full py-4 px-6 bg-rose-500/10 text-rose-500 font-bold rounded-2xl flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </button>
            </div>
        </div>
    </div>
</nav>