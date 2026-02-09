<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ChatBoat AI') }} - Admin Control Hub</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Outfit', sans-serif;
        }

        .premium-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(226, 232, 240, 0.8);
            backdrop-filter: blur(12px);
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="h-full bg-slate-50 text-slate-900 antialiased">
    <div x-data="{ mobileMenuOpen: false }" class="min-h-full flex">
        <!-- Premium Sidebar -->
        <div class="hidden lg:flex lg:flex-shrink-0">
            <div class="flex flex-col w-72 bg-white border-r border-slate-200 relative overflow-hidden">
                <!-- Background Glow -->
                <div
                    class="absolute -top-24 -left-24 w-64 h-64 bg-indigo-500/5 rounded-full blur-[80px] pointer-events-none">
                </div>

                <!-- Logo & Brand -->
                <div class="flex items-center h-20 flex-shrink-0 px-8 border-b border-slate-200">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform duration-300">
                            <x-application-logo class="w-6 h-6" />
                        </div>
                        <span
                            class="text-xl font-black bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600">Admin
                            Hub</span>
                    </a>
                </div>

                <!-- Admin Profile Mini -->
                <div class="px-6 py-8 border-b border-slate-200">
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 font-black text-xl border border-indigo-200 shadow-inner">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-black text-slate-900 truncate leading-none mb-1">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest leading-none">
                                Global Admin</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Scroll Area -->
                <nav class="flex-1 px-4 py-8 space-y-1 overflow-y-auto">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4 mb-4">Core Systems
                    </div>

                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600 border-l-4 border-indigo-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-4 border-transparent' }} group flex items-center px-4 py-3 text-sm font-bold transition-all duration-200">
                        <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>

                    <div class="pt-6">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4 mb-4">
                            Operations</div>

                        <a href="{{ route('admin.users') }}"
                            class="{{ request()->routeIs('admin.users') ? 'bg-emerald-50 text-emerald-600 border-l-4 border-emerald-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-4 border-transparent' }} group flex items-center px-4 py-3 text-sm font-bold transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            User Registry
                        </a>

                        <a href="{{ route('admin.chatbots') }}"
                            class="{{ request()->routeIs('admin.chatbots') ? 'bg-purple-50 text-purple-600 border-l-4 border-purple-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-4 border-transparent' }} group flex items-center px-4 py-3 text-sm font-bold transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            Bot Directory
                        </a>


                        <a href="{{ route('admin.user-billing') }}"
                            class="{{ request()->routeIs('admin.user-billing*') ? 'bg-purple-50 text-purple-600 border-l-4 border-purple-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-4 border-transparent' }} group flex items-center px-4 py-3 text-sm font-bold transition-all">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0 {{ request()->routeIs('admin.user-billing*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            User Billing
                        </a>

                        <a href="{{ route('admin.plans.index') }}"
                            class="{{ request()->routeIs('admin.plans.*') ? 'bg-pink-50 text-pink-600 border-l-4 border-pink-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-4 border-transparent' }} group flex items-center px-4 py-3 text-sm font-bold transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Revenue & Plans
                        </a>

                        <a href="{{ route('admin.addons.index') }}"
                            class="{{ request()->routeIs('admin.addons.*') ? 'bg-indigo-50 text-indigo-600 border-l-4 border-indigo-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-4 border-transparent' }} group flex items-center px-4 py-3 text-sm font-bold transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Manage Add-ons
                        </a>

                        <a href="{{ route('admin.payments') }}"
                            class="{{ request()->routeIs('admin.payments') ? 'bg-green-50 text-green-600 border-l-4 border-green-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-4 border-transparent' }} group flex items-center px-4 py-3 text-sm font-bold transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Payment Analytics
                        </a>

                        <a href="{{ route('admin.inquiries') }}"
                            class="{{ request()->routeIs('admin.inquiries') ? 'bg-amber-50 text-amber-600 border-l-4 border-amber-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-4 border-transparent' }} group flex items-center px-4 py-3 text-sm font-bold transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Support Inquiries
                        </a>

                        <a href="{{ route('admin.settings') }}"
                            class="{{ request()->routeIs('admin.settings') ? 'bg-indigo-50 text-indigo-600 border-l-4 border-indigo-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-4 border-transparent' }} group flex items-center px-4 py-3 text-sm font-bold transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            System Settings
                        </a>

                        <a href="{{ route('docs') }}"
                            class="{{ request()->routeIs('docs') ? 'bg-indigo-50 text-indigo-600 border-l-4 border-indigo-500' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 border-l-4 border-transparent' }} group flex items-center px-4 py-3 text-sm font-bold transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Documentation
                        </a>
                    </div>

                    <div class="pt-10">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-4 py-4 rounded-2xl bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-all group">
                            <div
                                class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 group-hover:text-slate-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-black uppercase tracking-widest text-slate-600 group-hover:text-slate-900">Customer
                                View</span>
                        </a>
                    </div>
                </nav>

                <!-- Logout -->
                <div class="p-6">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-3 px-4 py-4 rounded-2xl bg-rose-500/10 text-rose-500 font-black uppercase tracking-widest text-[10px] hover:bg-rose-500 hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Terminate Session
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Workspace -->
        <div class="flex-1 min-w-0 flex flex-col bg-slate-50 relative">
            <!-- Sublte background blobs -->
            <div
                class="absolute top-0 right-0 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[120px] pointer-events-none">
            </div>

            <!-- Mobile Navigation Trigger (Hidden on Desktop) -->
            <div class="lg:hidden flex items-center justify-between h-16 px-6 bg-white border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white">
                        <x-application-logo class="w-5 h-5" />
                    </div>
                    <span class="text-lg font-black">Admin Hub</span>
                </div>
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="p-2 rounded-xl text-slate-500 hover:bg-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Drawer -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 lg:hidden">
                <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="mobileMenuOpen = false"></div>
                <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full"
                    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                    class="relative w-72 h-full bg-white shadow-2xl flex flex-col overflow-y-auto">
                    <!-- Logo -->
                    <div class="flex items-center h-20 flex-shrink-0 px-8 border-b border-slate-200">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                                <x-application-logo class="w-6 h-6" />
                            </div>
                            <span
                                class="text-xl font-black bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600">Admin
                                Hub</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <nav class="flex-1 px-4 py-6 space-y-1">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] px-4 mb-4">Core
                            Systems</div>
                        <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false"
                            class="{{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" @click="mobileMenuOpen = false"
                            class="{{ request()->routeIs('admin.users') ? 'bg-emerald-50 text-emerald-600' : 'text-slate-600 hover:bg-slate-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            User Registry
                        </a>
                        <a href="{{ route('admin.chatbots') }}" @click="mobileMenuOpen = false"
                            class="{{ request()->routeIs('admin.chatbots') ? 'bg-purple-50 text-purple-600' : 'text-slate-600 hover:bg-slate-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            Bot Directory
                        </a>
                        <a href="{{ route('admin.payments') }}" @click="mobileMenuOpen = false"
                            class="{{ request()->routeIs('admin.payments') ? 'bg-green-50 text-green-600' : 'text-slate-600 hover:bg-slate-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Payments
                        </a>
                        <a href="{{ route('admin.plans.index') }}" @click="mobileMenuOpen = false"
                            class="{{ request()->routeIs('admin.plans.*') ? 'bg-pink-50 text-pink-600' : 'text-slate-600 hover:bg-slate-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Revenue & Plans
                        </a>
                        <a href="{{ route('admin.settings') }}" @click="mobileMenuOpen = false"
                            class="{{ request()->routeIs('admin.settings') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            System Settings
                        </a>
                        <a href="{{ route('docs') }}" @click="mobileMenuOpen = false"
                            class="{{ request()->routeIs('docs') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }} flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all">
                            <svg class="mr-3 h-5 w-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Documentation
                        </a>
                    </nav>

                    <!-- Back to User View -->
                    <div class="p-4 border-t border-slate-200">
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition-all">
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span class="text-xs font-black uppercase tracking-widest text-slate-600">Customer
                                View</span>
                        </a>
                    </div>

                    <!-- Logout -->
                    <div class="p-4">
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-3 px-4 py-4 rounded-2xl bg-rose-500/10 text-rose-500 font-black uppercase tracking-widest text-[10px] hover:bg-rose-500 hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <main class="flex-1 overflow-y-auto animate-fade-in relative z-10">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>