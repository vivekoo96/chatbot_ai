<x-admin-layout>
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Plan: {{ $plan->name }}</h2>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <div class="col-span-2">
                        <label
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Basic
                            Information</label>
                        <div class="h-px bg-gray-200 dark:bg-gray-700 mb-4"></div>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan Name</label>
                        <input type="text" name="name" value="{{ old('name', $plan->name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan Icon (SVG
                            Code)</label>
                        <p class="text-xs text-gray-500 mb-1">Paste the SVG icon code here. Use
                            <code>w-8 h-8 text-white</code> classes on the svg tag for best results.
                        </p>
                        <textarea name="svg_icon" rows="4"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono text-sm">{{ old('svg_icon', $plan->svg_icon) }}</textarea>
                        @error('svg_icon') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (INR)</label>
                        <input type="number" step="0.01" name="price_inr"
                            value="{{ old('price_inr', $plan->price_inr) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (USD)</label>
                        <input type="number" step="0.01" name="price_usd"
                            value="{{ old('price_usd', $plan->price_usd) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Limits Section -->
                    <div class="col-span-2 mt-4">
                        <label
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Usage
                            Limits</label>
                        <div class="h-px bg-gray-200 dark:bg-gray-700 mb-4"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Chatbots</label>
                        <input type="number" name="max_chatbots" value="{{ old('max_chatbots', $plan->max_chatbots) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max
                            Messages/Mo</label>
                        <input type="number" name="max_messages_per_month"
                            value="{{ old('max_messages_per_month', $plan->max_messages_per_month) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Image
                            Uploads/Mo</label>
                        <input type="number" name="max_image_uploads_per_month"
                            value="{{ old('max_image_uploads_per_month', $plan->max_image_uploads_per_month) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Voice
                            Minutes/Mo</label>
                        <input type="number" name="max_voice_minutes_per_month"
                            value="{{ old('max_voice_minutes_per_month', $plan->max_voice_minutes_per_month) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Max Team
                            Members</label>
                        <input type="number" name="max_team_users"
                            value="{{ old('max_team_users', $plan->max_team_users) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chat History
                            (Days)</label>
                        <input type="number" name="chat_history_days"
                            value="{{ old('chat_history_days', $plan->chat_history_days) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Support Level</label>
                        <select name="support_level"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="email" {{ $plan->support_level == 'email' ? 'selected' : '' }}>Email Support
                            </option>
                            <option value="priority" {{ $plan->support_level == 'priority' ? 'selected' : '' }}>Priority
                                Support</option>
                            <option value="dedicated" {{ $plan->support_level == 'dedicated' ? 'selected' : '' }}>
                                Dedicated Manager</option>
                        </select>
                    </div>

                    <!-- Feature Flags Section -->
                    <div class="col-span-2 mt-4">
                        <label
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Feature
                            Toggle</label>
                        <div class="h-px bg-gray-200 dark:bg-gray-700 mb-4"></div>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="allows_voice_messages" value="1" {{ $plan->allows_voice_messages ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Allow Voice Chat</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="allows_image_upload" value="1" {{ $plan->allows_image_upload ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Allow Image
                                Uploads</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="has_api_access" value="1" {{ $plan->has_api_access ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">API Access</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="has_lead_capture" value="1" {{ $plan->has_lead_capture ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Lead Capture Form</span>
                        </label>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="has_analytics_dashboard" value="1" {{ $plan->has_analytics_dashboard ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Analytics
                                Dashboard</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="has_role_based_access" value="1" {{ $plan->has_role_based_access ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span
                                class="text-sm font-medium text-gray-700 dark:text-gray-300 underline decoration-indigo-500/30">Dynamic
                                Role-Based Access</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="has_team_access" value="1" {{ $plan->has_team_access ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Team Management</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="has_advanced_analytics" value="1" {{ $plan->has_advanced_analytics ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Advanced Analytics</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="has_branding_removal" value="1" {{ $plan->has_branding_removal ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Remove Branding</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Plan is Active</span>
                        </label>
                    </div>

                    <!-- Marketplace Features Section -->
                    <div class="col-span-2 mt-4">
                        <label
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Display
                            Features (Pricing Cards)</label>
                        <div class="h-px bg-gray-200 dark:bg-gray-700 mb-4"></div>
                        <p class="text-xs text-gray-500 mb-2">Enter each feature on a new line. These will appear as
                            bullet points on the pricing page.</p>
                        <textarea name="features_json" rows="6"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono text-sm">{{ is_array($plan->features) ? implode("\n", $plan->features) : '' }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <a href="{{ route('admin.plans.index') }}"
                        class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>