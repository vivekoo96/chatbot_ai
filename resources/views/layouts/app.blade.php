<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ChatBoat AI') }}</title>

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

        .premium-card {
            background: rgba(255, 255, 255, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .dark .premium-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
        }

        [wire\:loading] {
            display: none;
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

<body class="h-full font-sans antialiased text-slate-900 bg-slate-50 dark:bg-slate-950 dark:text-slate-200">
    <div class="min-h-screen flex">
        <!-- Background Decorations -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div
                class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[120px]">
            </div>
            <div
                class="absolute bottom-0 right-0 translate-x-1/2 translate-y-1/2 w-[500px] h-[500px] bg-purple-500/5 rounded-full blur-[120px]">
            </div>
        </div>

        <!-- Sidebar Navigation -->
        <livewire:layout.navigation />

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen relative z-10 lg:pl-72 pt-16 lg:pt-0">
            @if(session()->has('admin_impersonator_id'))
                <div
                    class="sticky top-0 z-[60] bg-gradient-to-r from-indigo-600 to-purple-700 text-white px-4 py-3 shadow-2xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-widest leading-none">Impersonating User</p>
                            <p class="text-[10px] opacity-70 font-medium">You are currently logged in as <span
                                    class="font-bold underline">{{ auth()->user()->name }}</span></p>
                        </div>
                    </div>
                    <a href="{{ route('admin.stop-impersonating') }}"
                        class="px-4 py-2 bg-white text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg hover:bg-slate-50 transition-all active:scale-[0.98]">
                        Return to Admin Account
                    </a>
                </div>
            @endif

            <!-- Page Content -->
            <main class="flex-1 animate-fade-in py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
                {{ $slot }}
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</body>

</html>