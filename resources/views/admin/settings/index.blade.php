<x-layout>
    <x-slot name="header">
        Application Settings
    </x-slot>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Back Link -->
        <div class="mb-4">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                ← Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <x-toast type="success">{{ session('success') }}</x-toast>
        @endif

        @if(session('error'))
            <x-toast type="error">{{ session('error') }}</x-toast>
        @endif

        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
                <h2 class="text-xl font-bold text-white">Manage Application Settings</h2>
                <p class="text-blue-100 text-sm mt-1">Configure cart, tax, shipping, and notification settings</p>
            </div>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
                @csrf

                <div class="space-y-6">
                    <!-- Tax Settings -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tax Settings</h3>

                        <div>
                            <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">
                                Tax Rate (%)
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                name="tax_rate"
                                id="tax_rate"
                                value="{{ old('tax_rate', $settings['tax_rate']->value ?? '23.00') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tax_rate') border-red-500 @enderror"
                                required
                            >
                            @error('tax_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Current: {{ $settings['tax_rate']->value ?? '23.00' }}%</p>
                        </div>
                    </div>

                    <!-- Shipping Settings -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Settings</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-2">
                                    Shipping Cost (€)
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="shipping_cost"
                                    id="shipping_cost"
                                    value="{{ old('shipping_cost', $settings['shipping_cost']->value ?? '5.00') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('shipping_cost') border-red-500 @enderror"
                                    required
                                >
                                @error('shipping_cost')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Current: €{{ $settings['shipping_cost']->value ?? '5.00' }}</p>
                            </div>

                            <div>
                                <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700 mb-2">
                                    Free Shipping Threshold (€)
                                </label>
                                <input
                                    type="number"
                                    step="0.01"
                                    name="free_shipping_threshold"
                                    id="free_shipping_threshold"
                                    value="{{ old('free_shipping_threshold', $settings['free_shipping_threshold']->value ?? '50.00') }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('free_shipping_threshold') border-red-500 @enderror"
                                    required
                                >
                                @error('free_shipping_threshold')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Current: €{{ $settings['free_shipping_threshold']->value ?? '50.00' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Settings -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cart Settings</h3>

                        <div>
                            <label for="max_cart_quantity_per_book" class="block text-sm font-medium text-gray-700 mb-2">
                                Maximum Quantity Per Book
                            </label>
                            <input
                                type="number"
                                name="max_cart_quantity_per_book"
                                id="max_cart_quantity_per_book"
                                value="{{ old('max_cart_quantity_per_book', $settings['max_cart_quantity_per_book']->value ?? '10') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('max_cart_quantity_per_book') border-red-500 @enderror"
                                required
                            >
                            @error('max_cart_quantity_per_book')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Current: {{ $settings['max_cart_quantity_per_book']->value ?? '10' }} books</p>
                        </div>
                    </div>

                    <!-- Notification Settings -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Settings</h3>

                        <div>
                            <label for="abandoned_cart_hours" class="block text-sm font-medium text-gray-700 mb-2">
                                Abandoned Cart Hours
                            </label>
                            <input
                                type="number"
                                name="abandoned_cart_hours"
                                id="abandoned_cart_hours"
                                value="{{ old('abandoned_cart_hours', $settings['abandoned_cart_hours']->value ?? '1') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('abandoned_cart_hours') border-red-500 @enderror"
                                required
                            >
                            @error('abandoned_cart_hours')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Current: {{ $settings['abandoned_cart_hours']->value ?? '1' }} hour(s) - Time before sending abandoned cart notification</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex justify-end gap-3 border-t pt-6">
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">About Settings</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>These settings control the behavior of your cart, checkout, and notification systems. Changes take effect immediately for all users.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
