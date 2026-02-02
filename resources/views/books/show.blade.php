<x-layout>
    <x-slot name="header">
        Book Details
    </x-slot>
    <div class="container mx-auto py-4">
        <div class="mb-4">
            <strong>ISBN:</strong> {{ $book->isbn }}
        </div>
        <div class="mb-4">
            <strong>Name:</strong> {{ $book->name }}
        </div>
        <div class="mb-4">
            <strong>Bibliography:</strong> {{ $book->bibliography }}
        </div>
        <div class="mb-4">
            <strong>Publisher:</strong> {{ $book->publisher->name ?? '-' }}
        </div>
        <div class="mb-4">
            <strong>Authors:</strong>
            @foreach($book->authors as $author)
                <span class="badge">{{ $author->name }}</span>
            @endforeach
        </div>
        <div class="mb-4">
            <strong>Cover:</strong>
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover" class="h-12">
            @else
                <span>No cover uploaded.</span>
            @endif
        </div>
        <div class="mb-4">
            <strong>Price:</strong> {{ $book->price ? number_format($book->price, 2) : '-' }}
        </div>
        <div class="mb-4">
            <strong>Copies:</strong> {{ $book->copies }}
        </div>
        <div class="mb-4">
            <strong>Times Requested:</strong> {{ $book->requisitions_count }}
        </div>
        <div class="mb-4">
            <strong>Average Rating:</strong>
            @if($averageRating)
                {{ number_format($averageRating, 1) }} / 5 ({{ $reviewCount }} {{ Str::plural('review', $reviewCount) }})
            @else
                No reviews yet
            @endif
        </div>

        @auth
            @if(!auth()->user()->is_admin)
                <form action="{{ route('books.requisitions.store', $book) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Request Loan</button>
                </form>
            @endif
            @if(auth()->user()->is_admin)
                <a href="{{ route('books.edit', $book) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Edit</a>
            @endif
        @endauth
        <a href="{{ auth()->check() && auth()->user()->is_admin ? route('books.index') : route('home') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Back</a>

        <!-- Reviews Section -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Reviews</h2>
            @if($book->activeReviews->count() > 0)
                <div class="space-y-4">
                    @foreach($book->activeReviews as $review)
                        <div class="bg-gray-50 border border-gray-200 rounded p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold">{{ $review->user->name }}</span>
                                <span class="text-yellow-500">★ {{ $review->rating }} / 5</span>
                            </div>
                            @if($review->comment)
                                <p class="text-gray-700">{{ $review->comment }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600">No reviews yet. Be the first to review this book!</p>
            @endif
        </div>
    </div>
</x-layout>
