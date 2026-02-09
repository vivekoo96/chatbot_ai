<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ChatBoat AI') }} - {{ $title ?? 'Train AI Chatbots on Your Data' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles for Premium UI -->
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

        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .dark .glass-nav {
            background: rgba(17, 24, 39, 0.7);
        }

        .text-gradient {
            background: linear-gradient(135deg, #4F46E5 0%, #9333EA 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.4;
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }
    </style>
    {{ $head ?? '' }}
</head>

<body
    class="antialiased text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 selection:bg-indigo-500 selection:text-white flex flex-col min-h-screen">

    <!-- Premium Navigation -->
    <nav x-data="{ open: false }"
        class="glass-nav border-b border-slate-200/60 dark:border-slate-800/60 fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center group">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-all duration-300 transform group-hover:scale-105">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <span
                            class="ml-3 text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600 dark:from-white dark:to-slate-300">{{ config('app.name') }}</span>
                    </a>
                </div>

                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('features') }}"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors {{ request()->routeIs('features') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">Features</a>
                    <a href="{{ route('docs') }}"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors {{ request()->routeIs('docs') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">Docs</a>
                    <a href="{{ route('home') }}#demo"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Live
                        Demo</a>
                    <a href="{{ route('pricing') }}"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors {{ request()->routeIs('pricing') ? 'text-indigo-600 dark:text-indigo-400' : '' }}">Pricing</a>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-6 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-lg font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 px-4">Log
                                in</a>
                            <a href="{{ route('register') }}"
                                class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all duration-300 group">
                                Get Started Free
                                <span class="inline-block ml-1 transition-transform group-hover:translate-x-1">&rarr;</span>
                            </a>
                        @endauth
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-slate-800 transition duration-150">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-transition.origin.top
            class="md:hidden bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 shadow-xl">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="{{ route('features') }}"
                    class="block px-4 py-3 text-base font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600">Features</a>
                <a href="{{ route('docs') }}"
                    class="block px-4 py-3 text-base font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600">Docs</a>
                <a href="{{ route('home') }}#demo"
                    class="block px-4 py-3 text-base font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600">Live
                    Demo</a>
                <a href="{{ route('pricing') }}"
                    class="block px-4 py-3 text-base font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600">Pricing</a>
                <div class="border-t border-slate-100 dark:border-slate-800 my-2"></div>
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="block px-4 py-3 text-center rounded-lg bg-indigo-50 text-indigo-700 font-semibold">Go to
                        Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-4 py-3 text-base font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50">Log
                        in</a>
                    <a href="{{ route('register') }}"
                        class="block px-4 py-3 text-center rounded-lg bg-indigo-600 text-white font-semibold">Get Started
                        Free</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-20">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-2 md:col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <span class="font-bold text-xl text-slate-900 dark:text-white">{{ config('app.name') }}</span>
                    </a>
                    <p class="text-slate-500 text-sm mb-4">
                        Making AI accessible for every business. Train chatbots on your data in minutes.
                    </p>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-4">Product</h4>
                    <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                        <li><a href="{{ route('features') }}" class="hover:text-indigo-600 transition">Features</a></li>
                        <li><a href="{{ route('docs') }}" class="hover:text-indigo-600 transition">Docs</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-indigo-600 transition">Pricing</a></li>
                        <li><a href="{{ route('home') }}#demo" class="hover:text-indigo-600 transition">Live Demo</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-4">Company</h4>
                    <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                        <li><a href="{{ route('about') }}" class="hover:text-indigo-600 transition">About Us</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-indigo-600 transition">Contact</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-indigo-600 transition">Privacy Policy</a>
                        </li>
                        <li><a href="{{ route('terms') }}" class="hover:text-indigo-600 transition">Terms &
                                Conditions</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white mb-4">Connect</h4>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/hemnix" target="_blank"
                            class="text-slate-400 hover:text-indigo-500 transition" title="Facebook"><svg
                                class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg></a>
                        <a href="https://x.com/hemnixtechno" target="_blank"
                            class="text-slate-400 hover:text-indigo-500 transition" title="X (Twitter)"><svg
                                class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z" />
                            </svg></a>
                        <a href="https://www.instagram.com/hemnixtechnologies/" target="_blank"
                            class="text-slate-400 hover:text-indigo-500 transition" title="Instagram"><svg
                                class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg></a>
                        <a href="https://www.linkedin.com/in/hemnix-technologies/" target="_blank"
                            class="text-slate-400 hover:text-indigo-500 transition" title="LinkedIn"><svg
                                class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.761 0 5-2.239 5-5v-14c0-2.761-2.239-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                            </svg></a>
                    </div>
                </div>
            </div>

            <div
                class="border-t border-slate-200 dark:border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-slate-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Built for the future by <a
                        href="https://hemnix.com/" target="_blank"
                        class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">Hemnix
                        Technologies</a>.</p>
                <p class="mt-2 md:mt-0">v1.0.0</p>
            </div>
        </div>
    </footer>
</body>

</html>