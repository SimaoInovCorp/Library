<x-layout>
    <x-slot name="header">
        Home Page
    </x-slot>
    <div class="container mx-auto py-4">
        @if(session('success'))
            <x-toast type="success">{{ session('success') }}</x-toast>
        @endif
        @if(session('error'))
            <x-toast type="error">{{ session('error') }}</x-toast>
        @endif
        <!-- Featured Reviews Section -->
        @if($featuredReviews->count() > 0)
            <div class="mb-8 bg-blue-50 border border-blue-200 rounded p-6">
                <h2 class="text-2xl font-bold mb-4">📚 Featured Reviews</h2>
                <div class="space-y-4">
                    @foreach($featuredReviews as $review)
                        <div class="bg-white border border-gray-200 rounded p-4">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <a href="{{ route('books.show', $review->book) }}" class="font-semibold text-blue-600 hover:underline">{{ $review->book->name }}</a>
                                    <p class="text-sm text-gray-600">by {{ $review->user->name }}</p>
                                </div>
                                <span class="text-yellow-500 font-bold">★ {{ $review->rating }}/5</span>
                            </div>
                            @if($review->comment)
                                <p class="text-gray-700 text-sm">{{ Str::limit($review->comment, 150) }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <h2 class="text-2xl font-bold mb-4">Available Books</h2>
        <x-tables.books :books="App\Models\Book::with(['authors'])->withCount('requisitions')->get()" />
    </div>
</x-layout>