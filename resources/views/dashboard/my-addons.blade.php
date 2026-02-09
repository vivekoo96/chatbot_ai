<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Add-ons') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Active Add-ons --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Active Add-ons</h3>

                    @if($activeAddons->count() > 0)
                        <div class="space-y-4">
                            @foreach($activeAddons as $userAddon)
                                <div class="border dark:border-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <x-icon name="{{ $userAddon->addon->getIcon() }}"
                                                class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                                            <h4 class="font-bold">{{ $userAddon->addon->name }}</h4>
                                        </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Purchased:
                                                {{ $userAddon->purchased_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-semibold">
                                                {{ number_format($userAddon->quantity_remaining) }} /
                                                {{ number_format($userAddon->quantity_total) }}</div>
                                            <div class="text-xs text-gray-500">remaining</div>
                                        </div>
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mb-2">
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full"
                                                style="width: {{ $userAddon->getRemainingPercentage() }}%"></div>
                                        </div>
                                    </div>

                                    <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400">
                                        <span>{{ $userAddon->getUsagePercentage() }}% used</span>
                                        @if($userAddon->expires_at)
                                            <span>Expires: {{ $userAddon->expires_at->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p class="mb-4">You don't have any active add-ons</p>
                            <a href="{{ route('pricing') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                                Browse Add-ons →
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Purchase History --}}
            @if($expiredAddons->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold mb-4">Purchase History</h3>

                        <div class="space-y-3">
                            @foreach($expiredAddons as $userAddon)
                                <div class="border dark:border-gray-700 rounded-lg p-4 opacity-60">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-semibold">{{ $userAddon->addon->name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Purchased: {{ $userAddon->purchased_at->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="px-3 py-1 text-xs font-semibold rounded-full 
                                                    {{ $userAddon->status === 'expired' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($userAddon->status) }}
                                            </span>
                                            <p class="text-sm text-gray-500 mt-1">
                                                ₹{{ number_format($userAddon->amount_paid, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>