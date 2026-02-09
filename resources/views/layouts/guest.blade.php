<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ChatBoat AI') }} - Authentication</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

        .auth-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .dark .auth-card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .text-gradient {
            background: linear-gradient(135deg, #4F46E5 0%, #9333EA 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
    </style>
</head>

<body class="h-full antialiased text-slate-900 bg-white dark:bg-slate-950">
    <div class="flex min-h-full">
        <!-- Brand Column (Hidden on mobile) -->
        <div class="hidden lg:flex relative w-0 flex-1 bg-slate-950 overflow-hidden">
            <!-- Animated Background Mesh -->
            <div class="absolute inset-0 z-0 overflow-hidden">
                <div
                    class="absolute top-[-10%] left-[-10%] w-[60%] h-[60%] bg-indigo-600/20 rounded-full mix-blend-screen filter blur-[120px] animate-pulse">
                </div>
                <div class="absolute bottom-[-10%] right-[-10%] w-[60%] h-[60%] bg-purple-600/20 rounded-full mix-blend-screen filter blur-[120px] animate-pulse"
                    style="animation-delay: 2s"></div>
                <div class="absolute top-[20%] right-[10%] w-[40%] h-[40%] bg-blue-600/10 rounded-full mix-blend-screen filter blur-[100px] animate-pulse"
                    style="animation-delay: 4s"></div>

                <!-- Subtle Grid Pattern -->
                <div
                    class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 mix-blend-overlay">
                </div>
                <div class="absolute inset-0 shadow-[inner_0_0_100px_rgba(0,0,0,0.5)]"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-between p-16 w-full h-full">
                <!-- Top: Logo -->
                <div class="flex items-center gap-4">
                    <a href="/" class="group flex items-center gap-4">
                        <div
                            class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-2xl shadow-indigo-500/40 group-hover:scale-105 group-hover:rotate-3 transition-all duration-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-white tracking-tight">{{ config('app.name') }}</span>
                    </a>
                </div>

                <!-- Middle: Hero Text -->
                <div class="max-w-lg">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-bold uppercase tracking-widest mb-8 animate-in fade-in slide-in-from-left-4 duration-700">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        Next-Gen AI Platform
                    </div>

                    <h1 class="text-6xl font-black tracking-tight text-white mb-8 leading-[1.1]">
                        The Future of <br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">Customer
                            Chat</span>
                    </h1>

                    <p class="text-xl text-slate-400 leading-relaxed mb-12">
                        Build, train, and deploy custom AI agents that understand your business perfectly. No coding
                        required.
                    </p>

                    <!-- Features List -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-4 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="text-slate-300 font-medium tracking-tight">Real-time learning from your
                                data</span>
                        </div>
                        <div class="flex items-center gap-4 group">
                            <div
                                class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-purple-400 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <span class="text-slate-300 font-medium tracking-tight">Enterprise-grade security
                                built-in</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom: Stats -->
                <div class="grid grid-cols-2 gap-8 border-t border-white/5 pt-12">
                    <div class="space-y-1">
                        <div class="text-3xl font-black text-white tracking-tight">99.9%</div>
                        <div class="text-xs uppercase tracking-[0.2em] font-bold text-slate-500">Service Uptime</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-3xl font-black text-white tracking-tight">50M+</div>
                        <div class="text-xs uppercase tracking-[0.2em] font-bold text-slate-500">Daily Messages</div>
                    </div>
                </div>

                <!-- Subtle bottom footer -->
                <div class="absolute bottom-8 left-16 text-slate-600 text-[10px] font-bold uppercase tracking-widest">
                    &copy; {{ date('Y') }} {{ config('app.name') }} &bull; Intelligence Reimagined
                </div>
            </div>
        </div>

        <!-- Auth Form Column -->
        <div
            class="flex-1 flex flex-col justify-center py-10 px-4 sm:px-6 lg:flex-none lg:px-10 xl:px-16 bg-slate-50 dark:bg-slate-900 shadow-2xl z-10">
            <div class="mx-auto w-full max-w-full lg:max-w-4xl">
                <div class="lg:hidden text-center mb-10">
                    <a href="/" class="inline-flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ config('app.name') }}</span>
                    </a>
                </div>

                <div class="auth-card p-8 sm:p-12 rounded-3xl shadow-xl">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>

</html>