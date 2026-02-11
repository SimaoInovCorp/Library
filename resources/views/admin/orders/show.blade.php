<x-layout>
    <x-slot name="header">
        Order #{{ $order->order_number }}
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Back Link -->
        <div class="mb-4">
            <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                ← Back to All Orders
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Info -->
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700">
                        <h2 class="text-xl font-bold text-white">Order Information</h2>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Order Number</p>
                                <p class="text-lg font-bold text-gray-900">#{{ $order->order_number }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Order Date</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Status</p>
                                <div class="mt-1">
                                    @if($order->status === 'paid')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                    @elseif($order->status === 'pending')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending Payment
                                        </span>
                                    @elseif($order->status === 'failed')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Payment Failed
                                        </span>
                                    @elseif($order->status === 'cancelled')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Cancelled
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($order->paid_at)
                                <div>
                                    <p class="text-sm text-gray-600">Paid On</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $order->paid_at->format('M d, Y H:i') }}</p>
                                </div>
                            @endif
                        </div>

                        @if($order->payment_intent_id)
                            <div class="mt-4 pt-4 border-t">
                                <p class="text-sm text-gray-600 mb-1">Payment Intent ID</p>
                                <p class="text-sm text-gray-900 font-mono break-all">{{ $order->payment_intent_id }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- User Information -->
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-700">
                        <h2 class="text-xl font-bold text-white">Customer Information</h2>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center">
                                <svg class="h-8 w-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-lg font-bold text-gray-900">{{ $order->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $order->user->email }}</p>
                                <p class="text-xs text-gray-500">User ID: {{ $order->user->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Address -->
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-700">
                        <h2 class="text-xl font-bold text-white">Delivery Address</h2>
                    </div>

                    <div class="p-6">
                        <div class="text-gray-700">
                            <p class="font-semibold text-lg">{{ $order->delivery_address['name'] }}</p>
                            <p class="mt-2">{{ $order->delivery_address['address_line1'] }}</p>
                            @if($order->delivery_address['address_line2'])
                                <p>{{ $order->delivery_address['address_line2'] }}</p>
                            @endif
                            <p>
                                {{ $order->delivery_address['city'] }}@if($order->delivery_address['state']), {{ $order->delivery_address['state'] }}@endif
                                {{ $order->delivery_address['postal_code'] }}
                            </p>
                            <p>{{ $order->delivery_address['country'] }}</p>
                            <p class="mt-2">
                                <span class="text-gray-600">Phone:</span> {{ $order->delivery_address['phone'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-teal-700">
                        <h2 class="text-xl font-bold text-white">Order Items</h2>
                    </div>

                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex items-center border-b pb-4 last:border-b-0 last:pb-0">
                                    @if($item->book)
                                        @if($item->book->cover_path)
                                            <img src="{{ Storage::url($item->book->cover_path) }}" alt="{{ $item->book_name }}" class="w-16 h-24 object-cover rounded shadow">
                                        @else
                                            <div class="w-16 h-24 bg-gray-200 rounded shadow flex items-center justify-center">
                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-16 h-24 bg-gray-200 rounded shadow flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="ml-4 flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->book_name }}</h3>
                                        <p class="text-sm text-gray-600">ISBN: {{ $item->book_isbn }}</p>
                                        <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                                        @if($item->book)
                                            <p class="text-xs text-gray-500">Book ID: {{ $item->book_id }}</p>
                                        @else
                                            <p class="text-xs text-red-600">(Book no longer in system)</p>
                                        @endif
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm text-gray-600">€{{ number_format($item->price_at_purchase, 2) }} each</p>
                                        <p class="text-lg font-bold text-gray-900">€{{ number_format($item->total, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary & Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Order Summary -->
                <div class="bg-white shadow-xl rounded-lg overflow-hidden sticky top-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800">
                        <h2 class="text-xl font-bold text-white">Order Summary</h2>
                    </div>

                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold">€{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax ({{ number_format($order->tax_rate, 1) }}%)</span>
                                <span class="font-semibold">€{{ number_format($order->tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-semibold">
                                    @if($order->shipping == 0)
                                        <span class="text-green-600">FREE</span>
                                    @else
                                        €{{ number_format($order->shipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="border-t pt-3 mt-3">
                                <div class="flex justify-between">
                                    <span class="text-lg font-bold text-gray-900">Total</span>
                                    <span class="text-xl font-bold text-gray-900">€{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Actions -->
                @if($order->status !== 'cancelled')
                    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-red-600 to-pink-700">
                            <h2 class="text-xl font-bold text-white">Admin Actions</h2>
                        </div>

                        <div class="p-6">
                            @if($order->status !== 'paid')
                                <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                                        <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Cancel Order
                                    </button>
                                </form>
                                <p class="mt-2 text-xs text-gray-500 text-center">This action cannot be undone</p>
                            @else
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-blue-700">
                                                Paid orders cannot be cancelled. The inventory has already been decremented.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>