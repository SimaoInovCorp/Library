<x-layout>
    <x-slot name="header">
        Requisition Details
    </x-slot>
    <div class="container mx-auto py-4">
        <div class="bg-white shadow rounded p-6 mb-6">
            <h2 class="text-xl font-bold mb-2">Book: {{ $requisition->book->name }}</h2>
            <p><strong>Status:</strong> {{ ucfirst($requisition->status) }}</p>
            <p><strong>Requested at:</strong> {{ $requisition->requested_at }}</p>
            <p><strong>Expected end at:</strong> {{ $requisition->expected_end_at }}</p>
        </div>

        @if(session('success'))
            <x-toast type="success">{{ session('success') }}</x-toast>
        @endif
        @if(session('error'))
            <x-toast type="error">{{ session('error') }}</x-toast>
        @endif

        @if($requisition->review)
            <div class="bg-green-50 border border-green-200 rounded p-4 mb-4">
                <h3 class="font-semibold mb-2">Your Review</h3>
                <div class="mb-1">Rating: {{ $requisition->review->rating }} / 5</div>
                <div>{{ $requisition->review->comment }}</div>
                <form action="{{ route('reviews.destroy', $requisition->review) }}" method="POST" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Delete Review</button>
                </form>
            </div>
        @elseif(auth()->check() && !auth()->user()->is_admin && $requisition->user_id === auth()->id() && $requisition->status === 'returned')
            <div class="bg-gray-50 border border-gray-200 rounded p-4 mb-4">
                <h3 class="font-semibold mb-2">Leave a Review</h3>
                <form action="{{ route('reviews.store', [$requisition->book, $requisition]) }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label for="rating" class="block font-medium">Rating</label>
                        <select name="rating" id="rating" class="form-select w-24" required>
                            <option value="">Select</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-2">
                        <label for="comment" class="block font-medium">Comment</label>
                        <textarea name="comment" id="comment" class="form-textarea w-full" rows="3" maxlength="2000"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Submit Review</button>
                </form>
            </div>
        @endif

        <a href="{{ route('requisitions.index') }}" class="text-blue-600 hover:underline">Back to Requisitions</a>
    </div>
</x-layout>
