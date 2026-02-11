<x-layout>
    <x-slot name="header">
        Shopping Cart
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        @if($cart->isEmpty())
            <div class="bg-white shadow-xl rounded-lg p-8 text-center">
                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Your cart is empty</h3>
                <p class="mt-2 text-sm text-gray-500">Start adding books to your cart!</p>
                <div class="mt-6">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                        Go to Home
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
                            <h2 class="text-xl font-bold text-white">Cart Items ({{ $cart->total_items }})</h2>
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach($cart->items as $item)
                                <div class="p-6 flex items-start space-x-4">
                                    <!-- Book Cover -->
                                    <div class="flex-shrink-0 w-20 h-28 bg-gray-100 rounded flex items-center justify-center">
                                        @if($item->book->cover_image)
                                            <img src="{{ asset('storage/' . $item->book->cover_image) }}" alt="{{ $item->book->name }}" class="w-full h-full object-cover rounded">
                                        @else
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Book Details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            <a href="{{ route('books.show', $item->book) }}" class="hover:text-blue-600">{{ $item->book->name }}</a>
                                        </h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            @if($item->book->authors->count())
                                                By {{ $item->book->authors->pluck('name')->join(', ') }}
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500 mt-1">ISBN: {{ $item->book->isbn }}</p>
                                        <p class="text-lg font-bold text-gray-900 mt-2">€{{ number_format($item->price, 2) }}</p>

                                        <!-- Quantity Controls -->
                                        <div class="mt-3 flex items-center space-x-3">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center space-x-2">
                                                @csrf
                                                @method('PUT')
                                                <label for="quantity-{{ $item->id }}" class="text-sm font-medium text-gray-700">Qty:</label>
                                                <select id="quantity-{{ $item->id }}" name="quantity" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                    @for($i = 1; $i <= min(10, $item->book->copies); $i++)
                                                        <option value="{{ $i }}" {{ $item->quantity == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </form>

                                            <form action="{{ route('cart.destroy', $item) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Item Total -->
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-gray-900">€{{ number_format($item->total, 2) }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $item->quantity }} × €{{ number_format($item->price, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Clear Cart -->
                        <div class="px-6 py-4 bg-gray-50 border-t">
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                                @csrf
                                <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                    Clear Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-xl rounded-lg overflow-hidden sticky top-6">
                        <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-teal-700">
                            <h2 class="text-xl font-bold text-white">Order Summary</h2>
                        </div>

                        <div class="p-6 space-y-3">
                            <div class="flex justify-between text-base">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold text-gray-900">€{{ number_format($totals['subtotal'], 2) }}</span>
                            </div>

                            <div class="flex justify-between text-base">
                                <span class="text-gray-600">Tax ({{ number_format($totals['tax_rate'], 1) }}%)</span>
                                <span class="font-semibold text-gray-900">€{{ number_format($totals['tax'], 2) }}</span>
                            </div>

                            <div class="flex justify-between text-base">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-semibold text-gray-900">
                                    @if($totals['shipping'] == 0)
                                        <span class="text-green-600">FREE</span>
                                    @else
                                        €{{ number_format($totals['shipping'], 2) }}
                                    @endif
                                </span>
                            </div>

                            @if($totals['shipping'] > 0)
                                <p class="text-xs text-gray-500">
                                    Free shipping on orders over €{{ number_format($totals['free_shipping_threshold'], 2) }}
                                </p>
                            @endif

                            <div class="border-t pt-3 mt-3">
                                <div class="flex justify-between text-lg">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-gray-900">€{{ number_format($totals['total'], 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 pb-6">
                            <a href="{{ route('checkout.step1') }}" class="block w-full text-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                                Proceed to Checkout
                            </a>
                            <a href="{{ route('home') }}" class="block w-full text-center mt-3 px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>