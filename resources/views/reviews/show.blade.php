<x-layout>
    <x-slot name="header">
        Review Details
    </x-slot>
    <div class="container mx-auto py-4">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <!-- Book Information -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Book Information</h2>
                <div class="flex gap-4">
                    @if($review->book->cover_image)
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $review->book->cover_image) }}" alt="{{ $review->book->name }}" class="h-32 w-auto rounded shadow">
                        </div>
                    @endif
                    <div>
                        <p class="mb-2"><span class="font-semibold">Title:</span> {{ $review->book->name }}</p>
                        <p class="mb-2"><span class="font-semibold">ISBN:</span> {{ $review->book->isbn }}</p>
                        <p class="mb-2"><span class="font-semibold">Publisher:</span> {{ $review->book->publisher->name ?? 'N/A' }}</p>
                        <p class="mb-2"><span class="font-semibold">Authors:</span>
                            @foreach($review->book->authors as $author)
                                <span class="badge">{{ $author->name }}</span>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>

            <!-- Review Information -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Review Details</h2>
                <div class="mb-3">
                    <span class="font-semibold">Status:</span>
                    @if($review->status === 'suspended')
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Suspended</span>
                    @elseif($review->status === 'active')
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Active</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">Rejected</span>
                    @endif
                </div>
                <div class="mb-3">
                    <span class="font-semibold">Rating:</span>
                    <span class="text-yellow-500 text-lg">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                    </span>
                    <span class="text-gray-600">({{ $review->rating }}/5)</span>
                </div>
                <div class="mb-3">
                    <span class="font-semibold">Comment:</span>
                    <p class="mt-2 text-gray-700 bg-gray-50 p-4 rounded border border-gray-200">
                        {{ $review->comment ?? 'No comment provided.' }}
                    </p>
                </div>

                @auth
                    @if(Auth::user()->is_admin && $review->status === 'rejected' && $review->rejection_reason)
                        <div class="mb-3">
                            <span class="font-semibold text-red-700">Rejection Reason:</span>
                            <p class="mt-2 text-gray-700 bg-red-50 p-4 rounded border border-red-300">
                                {{ $review->rejection_reason }}
                            </p>
                        </div>
                    @endif
                @endauth

                <div class="mb-3">
                    <span class="font-semibold">Reviewed at:</span>
                    <span class="text-gray-600">{{ $review->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>

            <!-- User Information -->
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Reviewer Information</h2>
                <p class="mb-2"><span class="font-semibold">Name:</span> {{ $review->user->name }}</p>
                <p class="mb-2"><span class="font-semibold">Email:</span> {{ $review->user->email }}</p>
            </div>

            <!-- Requisition Information -->
            @if($review->requisition)
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Requisition Details</h2>
                    <p class="mb-2"><span class="font-semibold">Requisition #:</span> {{ $review->requisition->number }}</p>
                    <p class="mb-2"><span class="font-semibold">Status:</span>
                        <span class="badge bg-gray-500 text-white px-2 py-1 rounded text-xs">{{ ucfirst($review->requisition->status) }}</span>
                    </p>
                    <p class="mb-2"><span class="font-semibold">Requested at:</span> {{ $review->requisition->requested_at->format('Y-m-d H:i') }}</p>
                    @if($review->requisition->expected_end_at)
                        <p class="mb-2"><span class="font-semibold">Expected return:</span> {{ $review->requisition->expected_end_at->format('Y-m-d H:i') }}</p>
                    @endif
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex gap-2 mt-6">
                @auth
                    @if(Auth::user()->is_admin && $review->status === 'suspended')
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg font-bold shadow-lg hover:bg-green-700 transform hover:scale-105 transition duration-200">
                                Approve Review
                            </button>
                        </form>
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-bold shadow-lg hover:bg-red-700 transform hover:scale-105 transition duration-200">
                                Reject Review
                            </button>
                        </form>
                    @endif
                @endauth
                <a href="{{ route('books.show', $review->book) }}" class="inline-block">
                    <x-buttons.primary>View Book</x-buttons.primary>
                </a>
                @if($review->requisition)
                    <a href="{{ route('requisitions.show', $review->requisition) }}" class="inline-block">
                        <x-buttons.secondary>View Requisition</x-buttons.secondary>
                    </a>
                @endif
                <a href="{{ route('requisitions.index') }}" class="inline-block">
                    <x-buttons.secondary>Back to Requisitions</x-buttons.secondary>
                </a>
            </div>
        </div>
    </div>
</x-layout>
