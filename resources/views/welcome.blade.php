<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ChatBoat AI') }} - Train AI Chatbots Check on Your Data</title>

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
</head>

<body
    class="antialiased text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 selection:bg-indigo-500 selection:text-white">

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
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Features</a>
                    <a href="#demo"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Live
                        Demo</a>
                    <a href="{{ route('pricing') }}"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Pricing</a>
                    <a href="{{ route('docs') }}"
                        class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Docs</a>

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
                <a href="#demo"
                    class="block px-4 py-3 text-base font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600">Live
                    Demo</a>
                <a href="{{ route('pricing') }}"
                    class="block px-4 py-3 text-base font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600">Pricing</a>
                <a href="{{ route('docs') }}"
                    class="block px-4 py-3 text-base font-medium rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600">Docs</a>
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

    <!-- Hero Section -->
    <main class="relative pt-20 pb-20 lg:pt-28 lg:pb-32 overflow-hidden">
        <!-- Background Elements -->
        <div
            class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 pointer-events-none">
        </div>
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]">
        </div>

        <div
            class="blob w-96 h-96 bg-purple-300 rounded-full top-0 right-0 mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
        </div>
        <div
            class="blob w-96 h-96 bg-indigo-300 rounded-full bottom-0 left-0 mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
        </div>

        <!-- Decorative Bot Watermarks -->
        <div class="absolute top-1/4 left-10 text-indigo-500/10 animate-float pointer-events-none hidden lg:block">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 2a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v2a2 2 0 0 1-2 2v2a2 2 0 0 1-2 2h-2v2a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-2H6a2 2 0 0 1-2-2v-2a2 2 0 0 1-2-2v-2a2 2 0 0 1 2-2V8a2 2 0 0 1 2-2h2V4a2 2 0 0 1 2-2h4zm0 2H8v2h8V4h-4zM6 8v10h12V8H6zm3 2h2v2H9v-2zm4 0h2v2h-2v-2zm-4 4h6v2H9v-2z" />
            </svg>
        </div>
        <div class="absolute bottom-1/4 right-1/3 text-purple-500/10 animate-float pointer-events-none hidden lg:block"
            style="animation-delay: 3s">
            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h2c.55 0 1-.45 1-1s-.45-1-1-1H7c-.55 0-1 .45-1 1s.45 1 1 1zm8 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zm-8 4h10v2H7v-2z" />
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="text-left">
                    <div
                        class="inline-flex items-center px-4 py-2 rounded-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 mb-8 animate-float">
                        <span class="flex h-2 w-2 rounded-full bg-green-500 mr-2 animate-pulse"></span>
                        <span class="text-sm font-medium text-slate-600 dark:text-slate-300 flex items-center gap-2">
                            Live in 100+ Countries with Local Pricing
                            <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>

                    <h1
                        class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 dark:text-white mb-8 leading-tight">
                        Turn Your Data into a <br class="hidden md:block" />
                        <span class="text-gradient">Smart AI Assistant</span>
                    </h1>

                    <p class="mt-6 text-xl text-slate-600 dark:text-slate-400 max-w-2xl leading-relaxed">
                        Train ChatGPT on your website content, PDFs, and docs. Embed a support agent that answers
                        questions instantly, 24/7.
                        <span class="block mt-2 font-medium text-indigo-600 dark:text-indigo-400">No coding required.
                            Setup in 2 minutes.</span>
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}"
                            class="px-8 py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-xl font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                            Start Building for Free
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="#demo"
                            class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl font-semibold text-lg border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-all flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            View Live Demo
                        </a>
                    </div>

                    <div class="mt-12 flex items-center space-x-6 text-sm text-slate-500">
                        <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-green-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg> No credit card required</span>
                        <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-green-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg> Free tier available</span>
                    </div>
                </div>

                <div class="relative group">
                    <div
                        class="absolute -inset-4 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[2rem] blur-2xl opacity-20 group-hover:opacity-30 transition-opacity duration-500">
                    </div>
                    <div class="relative animate-float">
                        <img src="{{ asset('images/hero-ai.png') }}" alt="AI Assistant Visual"
                            class="w-full rounded-[2rem] shadow-2xl border border-white/10">

                        <!-- Floating Badge -->
                        <div
                            class="absolute -bottom-6 -right-6 bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 animate-bounce duration-[3000ms]">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs text-slate-500 font-medium">System Status</div>
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">AI Engine Active</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Live Demo Section -->
    <section id="demo" class="py-24 bg-slate-900 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-purple-600 rounded-full blur-3xl opacity-30">
        </div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 bg-indigo-600 rounded-full blur-3xl opacity-30">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <div
                        class="inline-block px-4 py-1.5 rounded-full bg-indigo-500/20 text-indigo-300 font-medium text-sm mb-6 border border-indigo-500/30">
                        Interactive Demo
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold mb-6 font-display">Experience the Magic</h2>
                    <p class="text-xl text-slate-300 mb-8 leading-relaxed">
                        This isn't just a screenshot. This is a real, live {{ config('app.name') }} instance trained on
                        our own documentation. Go ahead, ask it how to set up a bot!
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">Instance Responses</h3>
                                <p class="text-slate-400 mt-1">ChatBoat processes your content and responds in
                                    milliseconds.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 border border-purple-500/20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-white">Source Citations</h3>
                                <p class="text-slate-400 mt-1">The bot knows exactly where it got the answer from your
                                    data.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <!-- Mock Device -->
                    <div
                        class="relative mx-auto rounded-[2.5rem] border-[10px] border-slate-800 bg-slate-800 shadow-2xl h-[650px] w-[350px] md:w-[380px] overflow-hidden">
                        <div class="absolute top-0 inset-x-0 h-6 bg-slate-800 z-20 flex justify-center">
                            <div class="w-24 h-4 bg-black/50 rounded-b-xl backdrop-blur-sm"></div>
                        </div>

                        <div class="h-full w-full bg-white relative">
                            @if(isset($demoBot))
                                <iframe src="{{ route('widget.iframe', $demoBot->token) }}"
                                    class="w-full h-full border-none" title="ChatBoat Demo" loading="lazy"></iframe>
                            @else
                                <div class="flex flex-col items-center justify-center h-full p-8 text-center bg-slate-50">
                                    <div
                                        class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center mb-4 text-slate-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800 mb-2">Demo Bot Offline</h3>
                                    <p class="text-slate-500 text-sm">Create your first chatbot in the admin dashboard to
                                        activate this demo.</p>
                                    <a href="{{ route('register') }}"
                                        class="mt-6 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">Create
                                        Bot</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-24 bg-slate-50 dark:bg-slate-900/50 relative overflow-hidden">
        <!-- Decorative Blobs -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl -ml-20 -mt-20"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-500/5 rounded-full blur-3xl -mr-20 -mb-20"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20 animate-fade-in">
                <div
                    class="inline-flex items-center px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-full mb-6 border border-indigo-200 dark:border-indigo-800">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 mr-2" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                            clip-rule="evenodd" />
                    </svg>
                    <span
                        class="text-xs font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Pricing
                        Strategy</span>
                </div>

                <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Simple,
                    Transparent Pricing</h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                    Join companies worldwide choosing {{ config('app.name') }} to automate their support and boost
                    conversions with localized pricing options.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 items-stretch">
                @foreach($plans as $plan)
                    <div
                        class="group relative flex flex-col p-8 bg-white dark:bg-slate-800 rounded-[2.5rem] border-2 {{ $plan->slug === 'pro' ? 'border-indigo-500 shadow-2xl shadow-indigo-500/20 scale-105 z-10' : 'border-slate-200 dark:border-slate-700 shadow-sm' }} transition-all duration-500 hover:shadow-2xl">
                        @if($plan->slug === 'pro')
                            <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                                <span
                                    class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-[0.2em] shadow-lg shadow-indigo-500/40">Most
                                    Popular</span>
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 via-purple-500/5 to-pink-500/5 rounded-[2.5rem] pointer-events-none">
                            </div>
                        @endif

                        <div class="relative">
                            <!-- Plan Icon -->
                            <div
                                class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $plan->slug === 'free' ? 'from-slate-400 to-slate-600' : ($plan->slug === 'starter' ? 'from-blue-400 to-blue-600' : ($plan->slug === 'pro' ? 'from-indigo-500 to-purple-600' : 'from-purple-500 to-pink-600')) }} flex items-center justify-center mb-8 shadow-lg transform group-hover:scale-110 transition-transform duration-300">
                                @if($plan->svg_icon)
                                    {!! $plan->svg_icon !!}
                                @else
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                @endif
                            </div>

                            <h3 class="text-3xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">
                                {{ $plan->name }}
                            </h3>
                            <p class="text-slate-500 dark:text-slate-400 text-sm font-medium mb-8 leading-relaxed">
                                {{ $plan->description ?? 'Empower your business with professional AI tools' }}
                            </p>
                        </div>

                        <div class="mb-10 items-baseline flex relative">
                            <span
                                class="text-5xl font-black tracking-tighter text-slate-900 dark:text-white">{{ $plan->getFormattedPrice($currency) }}</span>
                            <span class="text-slate-400 dark:text-slate-500 text-sm font-bold ml-2">/month</span>
                        </div>

                        <div class="space-y-5 mb-10 flex-1 relative">
                            <div class="flex items-center text-sm font-bold text-slate-700 dark:text-slate-200">
                                <div
                                    class="w-6 h-6 rounded-lg bg-green-500/10 flex items-center justify-center mr-3 text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                {{ $plan->max_chatbots == -1 ? 'Unlimited' : $plan->max_chatbots }}
                                Chatbot{{ $plan->max_chatbots != 1 ? 's' : '' }}
                            </div>

                            <div class="flex items-center text-sm font-bold text-slate-700 dark:text-slate-200">
                                <div
                                    class="w-6 h-6 rounded-lg bg-green-500/10 flex items-center justify-center mr-3 text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                {{ $plan->max_messages_per_month == -1 ? 'Unlimited' : number_format($plan->max_messages_per_month) }}
                                Messages
                            </div>

                            @if($plan->allows_image_upload)
                                <div class="flex items-center text-sm font-bold text-slate-700 dark:text-slate-200">
                                    <div
                                        class="w-6 h-6 rounded-lg bg-green-500/10 flex items-center justify-center mr-3 text-green-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    Vision (Image) Support
                                </div>
                            @endif

                            @foreach(array_slice($plan->features ?? [], 0, 3) as $feature)
                                <li class="flex items-center text-sm font-medium text-slate-500 dark:text-slate-400">
                                    <div
                                        class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center mr-3 text-slate-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </div>

                        <a href="{{ route('pricing') }}"
                            class="group/btn block w-full py-4 px-6 rounded-2xl font-black text-center text-xs uppercase tracking-[0.2em] transition-all duration-300 transform active:scale-95 shadow-xl {{ $plan->slug === 'pro' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:shadow-indigo-500/40' : 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800' }}">
                            <span class="flex items-center justify-center gap-2">
                                {{ $plan->price_inr == 0 ? 'Start Building' : 'Choose ' . $plan->name }}
                                <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
    <!-- Add-ons Section -->
    @if(isset($addons) && $addons->count() > 0)
        <section id="addons" class="py-24 bg-white dark:bg-slate-900 relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-3xl mx-auto mb-20 animate-fade-in">
                    <div
                        class="inline-flex items-center px-4 py-2 bg-purple-100 dark:bg-purple-900/30 rounded-full mb-6 border border-purple-200 dark:border-purple-800">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 mr-2" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        <span
                            class="text-xs font-black text-purple-600 dark:text-purple-400 uppercase tracking-widest">Power
                            Ups</span>
                    </div>

                    <h2 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white mb-6 tracking-tight">Need More
                        Capacity?</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                        Boost your limits instantly with our focused add-ons. Perfect for seasonal spikes or rapidly growing
                        businesses.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 items-stretch">
                    @foreach($addons as $addon)
                        <div
                            class="group relative flex flex-col p-8 bg-slate-50 dark:bg-slate-800/50 rounded-[2.5rem] border-2 border-slate-100 dark:border-slate-700 transition-all duration-500 hover:shadow-2xl hover:bg-white dark:hover:bg-slate-800">
                            <div class="relative mb-8">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">
                                    {{ $addon->name }}
                                </h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm font-medium leading-relaxed mb-6">
                                    {{ $addon->description }}
                                </p>
                            </div>

                            <div class="mb-4 items-baseline flex">
                                <span
                                    class="text-4xl font-black tracking-tighter text-slate-900 dark:text-white">{{ $addon->getFormattedPrice($currency) }}</span>
                                <span class="text-slate-400 dark:text-slate-500 text-xs font-bold ml-2">ONE-TIME</span>
                            </div>
                            <div class="text-xs text-slate-400 font-bold mb-8 uppercase tracking-wider">
                                {{ $addon->getPerUnitCost($currency) }}
                            </div>

                            <div class="mt-auto">
                                <a href="{{ Auth::check() ? route('addons.checkout', $addon) : route('register') }}"
                                    class="group/btn block w-full py-4 px-6 rounded-2xl font-black text-center text-xs uppercase tracking-[0.2em] transition-all duration-300 transform active:scale-95 shadow-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800">
                                    <span class="flex items-center justify-center gap-2">
                                        Buy Now
                                        <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <div class="py-12 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-fade-in group">
                <p class="text-slate-500 font-medium mb-6">Need more flexibility? We offer custom solutions.</p>
                <a href="{{ route('pricing') }}"
                    class="inline-flex items-center px-8 py-3 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl text-indigo-600 dark:text-indigo-400 font-bold hover:border-indigo-500 transition-all shadow-lg hover:shadow-indigo-500/10">
                    View Detailed Pricing & Add-ons
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

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
                        <li><a href="{{ route('pricing') }}" class="hover:text-indigo-600 transition">Pricing</a></li>
                        <li><a href="#demo" class="hover:text-indigo-600 transition">Live Demo</a></li>
                        <li><a href="{{ route('docs') }}" class="hover:text-indigo-600 transition">Docs</a></li>
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