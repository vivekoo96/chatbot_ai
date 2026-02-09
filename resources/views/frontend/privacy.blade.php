<x-frontend-layout title="Privacy Policy">
    <div class="relative py-24 overflow-hidden">
        <!-- Background Blobs -->
        <div class="blob w-96 h-96 bg-indigo-500/10 top-0 -left-48"></div>
        <div class="blob w-96 h-96 bg-purple-500/10 bottom-0 -right-48"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16 animate-fade-in">
                <h1 class="text-5xl font-black text-slate-900 dark:text-white tracking-tight mb-4">
                    Privacy <span class="text-gradient">Policy</span>
                </h1>
                <p class="text-lg text-slate-500 dark:text-slate-400 font-medium tracking-tight">
                    Last updated: {{ date('F d, Y') }}
                </p>
            </div>

            <div class="premium-card p-10 md:p-16 rounded-[3rem] bg-white dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/50 shadow-2xl animate-fade-in prose dark:prose-invert prose-indigo max-w-none"
                style="animation-delay: 0.1s">
                @if($content)
                    <div class="whitespace-pre-wrap text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                        {!! nl2br(e($content)) !!}
                    </div>
                @else
                    <div class="text-center py-20 translate-y-4">
                        <div
                            class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 tracking-tight">Policy Content
                            Coming Soon</h3>
                        <p class="text-slate-500 dark:text-slate-400">We are currently finalizing our updated privacy
                            policy. Please check back later.</p>
                    </div>
                @endif
            </div>

            <div class="mt-12 text-center text-sm text-slate-500 animate-fade-in" style="animation-delay: 0.2s">
                <p>If you have any questions about this policy, please contact us at <a href="{{ route('contact') }}"
                        class="text-indigo-600 font-bold hover:underline">Support</a>.</p>
            </div>
        </div>
    </div>
</x-frontend-layout>