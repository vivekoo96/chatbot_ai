<x-admin-layout>
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Add-on: {{ $addon->name }}</h2>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <form action="{{ route('admin.addons.update', $addon) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Add-on Name</label>
                        <input type="text" name="name" value="{{ old('name', $addon->name) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                        <select name="type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="messages" {{ old('type', $addon->type) == 'messages' ? 'selected' : '' }}>Chat
                                Messages</option>
                            <option value="voice" {{ old('type', $addon->type) == 'voice' ? 'selected' : '' }}>Voice
                                Minutes</option>
                            <option value="images" {{ old('type', $addon->type) == 'images' ? 'selected' : '' }}>Image
                                Uploads</option>
                        </select>
                        @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $addon->quantity) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Price INR -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (INR)</label>
                        <input type="number" step="0.01" name="price_inr"
                            value="{{ old('price_inr', $addon->price_inr) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Price USD -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (USD)</label>
                        <input type="number" step="0.01" name="price_usd"
                            value="{{ old('price_usd', $addon->price_usd) }}" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $addon->description) }}</textarea>
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $addon->sort_order) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center mt-6">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $addon->is_active) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active Add-on</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <a href="{{ route('admin.addons.index') }}"
                        class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Add-on
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>