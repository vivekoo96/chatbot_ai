<x-frontend-layout>
    <x-slot name="title">Features</x-slot>

    <div class="py-20 lg:py-32 bg-white dark:bg-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <span class="text-indigo-600 font-semibold tracking-wide uppercase">Capabilities</span>
                <h1 class="mt-2 text-4xl font-extrabold text-slate-900 dark:text-white sm:text-5xl">
                    Everything you need to <span class="text-gradient">automate support</span>
                </h1>
                <p class="mt-4 text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Powerful features packaged in a simple interface.
                </p>
            </div>

            <!-- Feature 1 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center mb-24">
                <div>
                    <div
                        class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">One-Click Training</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                        Simply provide your website URL, sitemap, or upload PDF/Doc files.
                        {{ config('app.name', 'ChatBoat AI') }} scours your
                        content, understands the context, and trains itself instantly.
                        <br><br>
                        No need to create manual question-answer pairs or complex flowcharts. It just works.
                    </p>
                </div>
                <div class="relative group">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                    </div>
                    <div
                        class="relative bg-white dark:bg-slate-800 rounded-2xl p-6 ring-1 ring-slate-900/5 shadow-xl leading-none flex items-center justify-center aspect-video">
                        <div class="text-center">
                            <div class="animate-pulse mb-4">
                                <div class="h-2 bg-slate-200 rounded w-32 mx-auto mb-2"></div>
                                <div class="h-2 bg-slate-200 rounded w-24 mx-auto"></div>
                            </div>
                            <span class="text-slate-400 font-mono text-sm">Scanning https://yoursite.com...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center mb-24">
                <div class="order-2 md:order-1 relative group">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                    </div>
                    <div
                        class="relative bg-white dark:bg-slate-800 rounded-2xl p-6 ring-1 ring-slate-900/5 shadow-xl leading-none flex items-center justify-center aspect-video text-slate-400">
                        <!-- Customizer UI Mockup -->
                        <div class="w-full max-w-xs space-y-3">
                            <div class="flex items-center justify-between border-b pb-2">
                                <span class="text-xs">Chat Header</span>
                                <div class="w-4 h-4 rounded-full bg-indigo-500"></div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs">Primary Color</span>
                                <div class="h-2 bg-gradient-to-r from-purple-500 to-indigo-500 w-full rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-1 md:order-2">
                    <div
                        class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">Full Brand Customization</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                        Make the chatbot truly yours. Customize the avatar, color scheme, position, and welcome message
                        to align perfectly with your brand identity.
                        <br><br>
                        Remove ChatBoat branding (on Pro plans) for a completely white-label experience.
                    </p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="w-16 h-16 bg-pink-100 text-pink-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-4">Deep Analytics</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                        Don't just chat, learn. Understand what your customers are asking. View conversation logs, track
                        engagement rates, and identify gaps in your knowledge base.
                        <br><br>
                        Our dashboard gives you the insights you need to improve your products and documentation.
                    </p>
                </div>
                <div class="relative group">
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-pink-600 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200">
                    </div>
                    <div
                        class="relative bg-white dark:bg-slate-800 rounded-2xl p-6 ring-1 ring-slate-900/5 shadow-xl leading-none flex items-center justify-center aspect-video text-slate-400">
                        <div class="flex items-end space-x-2">
                            <div class="w-4 h-12 bg-slate-200 rounded"></div>
                            <div class="w-4 h-20 bg-indigo-500 rounded"></div>
                            <div class="w-4 h-16 bg-slate-200 rounded"></div>
                            <div class="w-4 h-24 bg-purple-500 rounded"></div>
                            <div class="w-4 h-10 bg-slate-200 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>