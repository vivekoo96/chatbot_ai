<div class="p-8 lg:p-12">
    @if($successMessage)
        <div
            class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 p-6 rounded-2xl mb-8 animate-fade-in text-center">
            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="font-black uppercase tracking-widest text-xs">{{ $successMessage }}</p>
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
            <input wire:model="name" type="text" id="name"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                placeholder="John Doe">
            @error('name') <span
                class="text-rose-500 text-[10px] font-bold mt-1 uppercase tracking-widest">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
            <input wire:model="email" type="email" id="email"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                placeholder="john@example.com">
            @error('email') <span
                class="text-rose-500 text-[10px] font-bold mt-1 uppercase tracking-widest">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="subject" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Subject
                (Optional)</label>
            <input wire:model="subject" type="text" id="subject"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                placeholder="How can we help?">
        </div>

        <div>
            <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Message</label>
            <textarea wire:model="message" id="message" rows="4"
                class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
                placeholder="Tell us more about your project..."></textarea>
            @error('message') <span
                class="text-rose-500 text-[10px] font-bold mt-1 uppercase tracking-widest">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" wire:loading.attr="disabled"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors disabled:opacity-50">
            <span wire:loading.remove>Send Message</span>
            <span wire:loading>Processing...</span>
        </button>
    </form>
</div>