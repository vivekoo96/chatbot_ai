<x-frontend-layout>
    <x-slot name="title">About Us</x-slot>

    <div class="relative py-20 lg:py-32 overflow-hidden bg-slate-50 dark:bg-slate-900">
        <!-- Background Blobs -->
        <div
            class="blob w-96 h-96 bg-purple-300 rounded-full top-0 right-0 mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
        </div>
        <div
            class="blob w-96 h-96 bg-indigo-300 rounded-full bottom-0 left-0 mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white sm:text-5xl mb-6">
                    We're building the future of <br class="hidden sm:block" />
                    <span class="text-gradient">Automated Customer Support</span>
                </h1>
                <p class="mt-4 text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Democratizing Artificial Intelligence for businesses of all sizes.
                </p>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-3xl p-8 lg:p-12 shadow-xl border border-slate-200 dark:border-slate-700">
                <div class="prose prose-lg dark:prose-invert mx-auto">
                    <p class="lead text-lg text-slate-700 dark:text-slate-300">
                        {{ config('app.name', 'ChatBoat AI') }} was born from a simple idea: <strong>Artificial
                            Intelligence should be accessible to
                            every business, regardless of size or technical budget.</strong>
                    </p>

                    <div class="grid md:grid-cols-2 gap-12 my-12 items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4">Our Mission</h3>
                            <p class="text-slate-600 dark:text-slate-400">
                                We believe that automated customer support shouldn't require a team of engineers. It
                                should be as simple as pasting a link to your website.
                                Our goal is to empower small and medium businesses with state-of-the-art Large Language
                                Models (LLMs) that can understand context, answer complex queries, and drive sales 24/7.
                            </p>
                        </div>
                        <div class="relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl transform rotate-2 opacity-50">
                            </div>
                            <div
                                class="relative bg-slate-50 dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-700">
                                <h4 class="font-bold text-lg mb-2">The Old Way</h4>
                                <ul class="list-disc pl-5 mb-4 text-sm text-slate-500">
                                    <li>Hiring expensive support agents</li>
                                    <li>Building complex decision trees</li>
                                    <li>Slow response times</li>
                                </ul>
                                <h4 class="font-bold text-lg mb-2 text-indigo-600">The ChatBoat Way</h4>
                                <ul class="list-disc pl-5 text-sm text-slate-600 dark:text-slate-400">
                                    <li>Instant AI Training on your data</li>
                                    <li>24/7 Availability</li>
                                    <li>95% Cost Reduction</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-center text-3xl font-bold mb-8">Our Core Values</h3>
                    <div class="grid md:grid-cols-3 gap-8 not-prose">
                        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-xl">
                            <div
                                class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4 text-2xl font-bold">
                                1</div>
                            <h4 class="text-xl font-bold mb-2">Simplicity</h4>
                            <p class="text-slate-600 dark:text-slate-400">If it needs a manual, it's too complex. We
                                build for speed and ease of use.</p>
                        </div>
                        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-xl">
                            <div
                                class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-4 text-2xl font-bold">
                                2</div>
                            <h4 class="text-xl font-bold mb-2">Speed</h4>
                            <p class="text-slate-600 dark:text-slate-400">In business, every second counts. Our bots
                                respond instantly.</p>
                        </div>
                        <div class="p-6 bg-slate-50 dark:bg-slate-900/50 rounded-xl">
                            <div
                                class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mb-4 text-2xl font-bold">
                                3</div>
                            <h4 class="text-xl font-bold mb-2">Transparency</h4>
                            <p class="text-slate-600 dark:text-slate-400">No hidden fees, no opaque algorithms. You own
                                your data.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-frontend-layout>