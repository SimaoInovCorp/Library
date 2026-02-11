<x-layout>
    <x-slot name="header">
        Checkout - Payment
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full font-bold">✓</div>
                    <span class="ml-2 text-sm font-medium text-green-600">Review Cart</span>
                </div>
                <div class="w-24 h-1 bg-green-600 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white rounded-full font-bold">✓</div>
                    <span class="ml-2 text-sm font-medium text-green-600">Delivery</span>
                </div>
                <div class="w-24 h-1 bg-blue-600 mx-4"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full font-bold">3</div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Payment</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="px-6 py-4 bg-gradient-to-r from-green-600 to-teal-700">
                        <h2 class="text-xl font-bold text-white">Payment Information</h2>
                    </div>

                    <div class="p-6">
                        <!-- Delivery Address Summary -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-2">Delivering to:</h3>
                            <p class="text-sm text-gray-700">{{ $order->delivery_address['name'] }}</p>
                            <p class="text-sm text-gray-600">{{ $order->delivery_address['address_line1'] }}</p>
                            @if($order->delivery_address['address_line2'])
                                <p class="text-sm text-gray-600">{{ $order->delivery_address['address_line2'] }}</p>
                            @endif
                            <p class="text-sm text-gray-600">
                                {{ $order->delivery_address['city'] }}
                                @if($order->delivery_address['state']), {{ $order->delivery_address['state'] }}@endif
                                {{ $order->delivery_address['postal_code'] }}
                            </p>
                            <p class="text-sm text-gray-600">{{ $order->delivery_address['country'] }}</p>
                            <p class="text-sm text-gray-600">{{ $order->delivery_address['phone'] }}</p>
                        </div>

                        <!-- Payment Form -->
                        <form id="payment-form" class="space-y-6">
                            <div>
                                <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">
                                    Card Information
                                </label>
                                <div id="card-element" class="p-3 border border-gray-300 rounded-md">
                                    <!-- Stripe Elements will insert the card input here -->
                                </div>
                                <div id="card-errors" class="mt-2 text-sm text-red-600" role="alert"></div>
                            </div>

                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            <strong>Test Mode:</strong> Use card <code class="bg-white px-1 rounded">4242 4242 4242 4242</code> with any future expiry and CVC.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div id="payment-message" class="hidden p-4 rounded-md"></div>

                            <div class="flex justify-between pt-6 border-t">
                                <a href="{{ route('checkout.step2') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300">
                                    ← Back
                                </a>
                                <button type="submit" id="submit-button" class="px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span id="button-text">Pay €{{ number_format($order->total, 2) }}</span>
                                    <span id="spinner" class="hidden">Processing...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white shadow-xl rounded-lg overflow-hidden sticky top-6">
                    <div class="px-6 py-4 bg-gradient-to-r from-gray-700 to-gray-800">
                        <h2 class="text-xl font-bold text-white">Order Summary</h2>
                        <p class="text-sm text-gray-300 mt-1">Order #{{ $order->order_number }}</p>
                    </div>

                    <div class="p-6">
                        <!-- Order Items -->
                        <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                            @foreach($order->items as $item)
                                <div class="flex justify-between text-sm">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ $item->book_name }}</p>
                                        <p class="text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <p class="font-semibold text-gray-900">€{{ number_format($item->total, 2) }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold">€{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax ({{ number_format($order->tax_rate, 1) }}%)</span>
                                <span class="font-semibold">€{{ number_format($order->tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-semibold">
                                    @if($order->shipping == 0)
                                        <span class="text-green-600">FREE</span>
                                    @else
                                        €{{ number_format($order->shipping, 2) }}
                                    @endif
                                </span>
                            </div>
                            <div class="border-t pt-2 mt-2">
                                <div class="flex justify-between">
                                    <span class="text-base font-bold text-gray-900">Total</span>
                                    <span class="text-lg font-bold text-gray-900">€{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('services.stripe.key') }}');
        const elements = stripe.elements();

        // Custom styling for card element
        const style = {
            base: {
                fontSize: '16px',
                color: '#32325d',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        const cardElement = elements.create('card', { style: style });
        cardElement.mount('#card-element');

        // Handle real-time validation errors
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');
        const messageDiv = document.getElementById('payment-message');

        form.addEventListener('submit', async function(event) {
            event.preventDefault();

            // Disable submit button and show spinner
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');

            try {
                // Confirm the payment with Stripe
                const { error, paymentIntent } = await stripe.confirmCardPayment(
                    '{{ $paymentIntent->client_secret }}',
                    {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: '{{ $order->delivery_address['name'] }}',
                                phone: '{{ $order->delivery_address['phone'] }}'
                            }
                        }
                    }
                );

                if (error) {
                    // Show error to customer
                    showMessage(error.message, 'error');
                    submitButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                } else if (paymentIntent.status === 'succeeded') {
                    // Payment succeeded - notify backend
                    const response = await fetch('{{ route('checkout.processPayment', $order) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        showMessage('Payment successful! Redirecting...', 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 1000);
                    } else {
                        showMessage(data.message || 'Payment processing failed.', 'error');
                        submitButton.disabled = false;
                        buttonText.classList.remove('hidden');
                        spinner.classList.add('hidden');
                    }
                }
            } catch (err) {
                showMessage('An unexpected error occurred.', 'error');
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        });

        function showMessage(text, type) {
            messageDiv.textContent = text;
            messageDiv.classList.remove('hidden', 'bg-red-50', 'text-red-800', 'border-red-400', 'bg-green-50', 'text-green-800', 'border-green-400');

            if (type === 'error') {
                messageDiv.classList.add('bg-red-50', 'text-red-800', 'border-l-4', 'border-red-400');
            } else {
                messageDiv.classList.add('bg-green-50', 'text-green-800', 'border-l-4', 'border-green-400');
            }
        }
    </script>
</x-layout>