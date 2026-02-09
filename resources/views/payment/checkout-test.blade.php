{{-- Test if component renders --}}
<x-guest-layout title="Complete Your Subscription">
    <div class="min-h-screen flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-950 p-4">
        <div class="max-w-md w-full bg-white dark:bg-slate-900 rounded-lg shadow-lg p-8 text-center">
            <h1 class="text-3xl font-bold mb-4">Component Test</h1>
            <p class="text-slate-600 dark:text-slate-400 mb-6">If you see this, the guest-layout component is working!</p>
            
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-6 text-left">
                <h2 class="font-bold mb-2">Debug Info:</h2>
                <ul class="text-sm space-y-1">
                    <li><strong>Vite loaded:</strong> Yes (CSS/JS are active)</li>
                    <li><strong>Component:</strong> guest-layout</li>
                    <li><strong>Title:</strong> Complete Your Subscription</li>
                    <li><strong>Page:</strong> checkout-test</li>
                </ul>
            </div>
            
            <a href="{{ route('pricing') }}" class="inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Back to Pricing
            </a>
        </div>
    </div>
</x-guest-layout>
