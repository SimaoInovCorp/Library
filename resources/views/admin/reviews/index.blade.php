<x-layout>
    <x-slot name="header">
        Review Moderation
    </x-slot>
    <div class="container mx-auto py-4">
            <!-- Status Filter Tabs -->
            <div class="mb-6 flex space-x-4">
                <x-buttons.tab href="{{ route('admin.reviews.index', ['status' => 'suspended']) }}" :active="$status === 'suspended'" color="yellow">Suspended</x-buttons.tab>
                <x-buttons.tab href="{{ route('admin.reviews.index', ['status' => 'active']) }}" :active="$status === 'active'" color="green">Active</x-buttons.tab>
                <x-buttons.tab href="{{ route('admin.reviews.index', ['status' => 'rejected']) }}" :active="$status === 'rejected'" color="red">Rejected</x-buttons.tab>
            </div>

            <!-- Reviews List -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if($reviews->isEmpty())
                        <p class="text-gray-500 text-center py-8">No {{ $status }} reviews found.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($reviews as $review)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <!-- Book and User Info -->
                                            <div class="flex items-start space-x-4">
                                                @if($review->book->cover_image)
                                                    <img src="{{ asset('storage/' . $review->book->cover_image) }}"
                                                         alt="{{ $review->book->title }}"
                                                         class="w-16 h-24 object-cover rounded shadow">
                                                @endif
                                                <div class="flex-1">
                                                    <h3 class="font-bold text-lg">
                                                        <a href="{{ route('books.show', $review->book) }}" class="text-blue-600 hover:text-blue-800">
                                                            {{ $review->book->title }}
                                                        </a>
                                                    </h3>
                                                    <p class="text-sm text-gray-600">
                                                        by {{ $review->book->authors->pluck('name')->join(', ') }}
                                                    </p>

                                                    <!-- Rating -->
                                                    <div class="flex items-center mt-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                                                 fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                            </svg>
                                                        @endfor
                                                        <span class="ml-2 text-sm text-gray-600">({{ $review->rating }}/5)</span>
                                                    </div>

                                                    <!-- Review Comment -->
                                                    @if($review->comment)
                                                        <p class="mt-2 text-gray-700">{{ $review->comment }}</p>
                                                    @endif

                                                    <!-- User and Date -->
                                                    <div class="mt-3 text-sm text-gray-500">
                                                        <span class="font-medium">Reviewed by:</span> {{ $review->user->name }}
                                                        <span class="mx-2">•</span>
                                                        {{ $review->created_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="ml-4 flex flex-col space-y-2">
                                            <form method="GET" action="{{ route('reviews.show', $review) }}" style="display:inline;">
                                                <x-buttons.edit type="submit">Details</x-buttons.edit>
                                            </form>

                                            @if($review->status === 'suspended')
                                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <x-buttons.success type="submit">Approve</x-buttons.success>
                                                </form>
                                                <button type="button" class="reject-btn px-4 py-2 bg-red-600 text-white rounded-lg font-bold shadow-lg hover:bg-red-700 transform hover:scale-105 transition duration-200 text-sm" data-review-id="{{ $review->id }}" data-book-name="{{ $review->book->name }}">
                                                    Reject
                                                </button>
                                            @elseif($review->status === 'active')
                                                <button type="button" class="reject-btn px-4 py-2 bg-red-600 text-white rounded-lg font-bold shadow-lg hover:bg-red-700 transform hover:scale-105 transition duration-200 text-sm" data-review-id="{{ $review->id }}" data-book-name="{{ $review->book->name }}">
                                                    Reject
                                                </button>
                                            @elseif($review->status === 'rejected')
                                                <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <x-buttons.success type="submit">Approve</x-buttons.success>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
    </div>

    <!-- Rejection Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Reject Review</h3>
                <p class="text-sm text-gray-600 mb-4">Are you sure you want to reject the review for "<span id="modalBookName" class="font-semibold"></span>"?</p>

                <form id="rejectForm" method="POST" action="">
                    @csrf
                    <div class="mb-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-gray-500">(Optional)</span>
                        </label>
                        <textarea
                            id="rejection_reason"
                            name="rejection_reason"
                            rows="4"
                            maxlength="1000"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                            placeholder="Explain why this review is being rejected..."
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
                    </div>

                    <div class="flex gap-2 justify-end">
                        <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Confirm Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Event delegation for reject buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.reject-btn')) {
                const btn = e.target.closest('.reject-btn');
                const reviewId = btn.dataset.reviewId;
                const bookName = btn.dataset.bookName;
                openRejectModal(reviewId, bookName);
            }
        });

        function openRejectModal(reviewId, bookName) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('modalBookName').textContent = bookName;
            document.getElementById('rejectForm').action = `/admin/reviews/${reviewId}/reject`;
            document.getElementById('rejection_reason').value = '';
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRejectModal();
            }
        });
    </script>
</x-layout>
