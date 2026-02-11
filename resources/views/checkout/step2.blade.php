<x-layout>
    <x-slot name="header">
        Checkout - Delivery Address
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full font-bold">✓</div>
                    <span class="ml-2 text-sm font-medium text-green-600">Review Cart</span>
                </div>
                <div class="w-24 h-1 bg-blue-600 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold">2</div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Delivery</span>
                </div>
                <div class="w-24 h-1 bg-gray-300 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full font-bold">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-600">Payment</span>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="max-w-3xl mx-auto">
            <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
                    <h2 class="text-xl font-bold text-white">Delivery Address</h2>
                </div>

                <div class="p-6">
                    <form action="{{ route('checkout.saveAddress') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Saved Addresses -->
                        @if($addresses->count() > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Use Saved Address
                                </label>
                                <select name="address_id" id="address_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" onchange="toggleAddressForm()">
                                    <option value="">Enter new address...</option>
                                    @foreach($addresses as $addr)
                                        <option value="{{ $addr->id }}" {{ $defaultAddress && $defaultAddress->id == $addr->id ? 'selected' : '' }}>
                                            {{ $addr->name }} - {{ $addr->address_line1 }}, {{ $addr->city }} {{ $addr->postal_code }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="border-t pt-4">
                                <p class="text-sm text-gray-600 mb-4">Or enter a new address:</p>
                            </div>
                        @endif

                        <!-- New Address Form -->
                        <div id="address-form" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="address_line1" class="block text-sm font-medium text-gray-700">Address Line 1 *</label>
                                    <input type="text" name="address_line1" id="address_line1" value="{{ old('address_line1') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('address_line1')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="address_line2" class="block text-sm font-medium text-gray-700">Address Line 2</label>
                                    <input type="text" name="address_line2" id="address_line2" value="{{ old('address_line2') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">City *</label>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700">State/Province</label>
                                    <input type="text" name="state" id="state" value="{{ old('state') }}" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code *</label>
                                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700">Country *</label>
                                    <input type="text" name="country" id="country" value="{{ old('country', 'Portugal') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('country')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="save_address" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Save this address for future orders</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-between pt-6 border-t">
                            <a href="{{ route('checkout.step1') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300">
                                ← Back
                            </a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                                Continue to Payment →
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAddressForm() {
            const addressId = document.getElementById('address_id').value;
            const form = document.getElementById('address-form');
            const inputs = form.querySelectorAll('input');

            if (addressId) {
                inputs.forEach(input => {
                    if (input.type !== 'checkbox') {
                        input.removeAttribute('required');
                    }
                });
            } else {
                document.querySelectorAll('[name="name"], [name="address_line1"], [name="city"], [name="postal_code"], [name="country"], [name="phone"]').forEach(input => {
                    input.setAttribute('required', 'required');
                });
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('address_id')) {
                toggleAddressForm();
            }
        });
    </script>
</x-layout>