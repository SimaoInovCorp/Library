<x-layout>
    <x-slot name="header">
        Checkout - Review Cart
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold">1</div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Review Cart</span>
                </div>
                <div class="w-24 h-1 bg-gray-300 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full font-bold">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-600">Delivery</span>
                </div>
                <div class="w-24 h-1 bg-gray-300 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-gray-300 text-gray-600 rounded-full font-bold">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-600">Payment</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
                        <h2 class="text-xl font-bold text-white">Order Items ({{ $cart->total_items }})</h2>
                    </div>

                    <div class="divide-y divide-gray-200">
                        @foreach($cart->items as $item)
                            <div class="p-6 flex items-start space-x-4">
                                <div class="flex-shrink-0 w-16 h-24 bg-gray-100 rounded flex items-center justify-center">
                                    @if($item->book->cover_image)
                                        <img src="{{ asset('storage/' . $item->book->cover_image) }}" alt="{{ $item->book->name }}" class="w-full h-full object-cover rounded">
                                    @else
                                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <h3 class="text-base font-semibold text-gray-900">{{ $item->book->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">Quantity: {{ $item->quantity }}</p>
                                    <p class="text-sm text-gray-500">€{{ number_format($item->price, 2) }} each</p>
                                </div>

                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">€{{ number_format($item->total, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('cart.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        ← Back to Cart
                    </a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-teal-700">
                        <h2 class="text-xl font-bold text-white">Order Summary</h2>
                    </div>

                    <div class="p-6 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">€{{ number_format($totals['subtotal'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax ({{ number_format($totals['tax_rate'], 1) }}%)</span>
                            <span class="font-semibold">€{{ number_format($totals['tax'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-semibold">
                                @if($totals['shipping'] == 0)
                                    <span class="text-green-600">FREE</span>
                                @else
                                    €{{ number_format($totals['shipping'], 2) }}
                                @endif
                            </span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg">
                                <span class="font-bold">Total</span>
                                <span class="font-bold">€{{ number_format($totals['total'], 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 pb-6">
                        <a href="{{ route('checkout.step2') }}" class="block w-full text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                            Continue to Delivery →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>