<x-admin-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-10 animate-fade-in">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                                Financial Management
                            </span>
                        </div>
                        <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">User Billing
                            Overview</h1>
                        <p class="text-lg text-slate-500 dark:text-slate-400 mt-2 font-medium">Manage user
                            subscriptions, plans, and detailed billing information.</p>
                    </div>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="mb-6">
                <form method="GET" action="{{ route('admin.user-billing') }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..."
                        class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Search
                    </button>
                    @if($search)
                        <a href="{{ route('admin.user-billing') }}"
                            class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Users Table --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">User
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usage
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Next
                                        Billing</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3">
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($user->plan)
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold rounded-full
                                                                        {{ $user->plan->slug === 'free' ? 'bg-gray-100 text-gray-800' : 'bg-indigo-100 text-indigo-800' }}">
                                                    {{ $user->plan->name }}
                                                </span>
                                            @else
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    No Plan
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($user->activeSubscription)
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    Free
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <div>💬 {{ number_format($user->messages_this_month ?? 0) }} msgs</div>
                                            <div>🤖
                                                {{ $user->chatbots->where('is_active', true)->count() }}/{{ $user->chatbots->count() }}
                                                bots</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @if($user->activeSubscription && $user->activeSubscription->next_billing_at)
                                                {{ $user->activeSubscription->next_billing_at->format('M d, Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <a href="{{ route('admin.user-billing.show', $user) }}"
                                                class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                                View Details →
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                            No users found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>