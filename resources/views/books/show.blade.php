<x-layout>
    <x-slot name="header">
        Book Details
    </x-slot>
    <div class="container mx-auto py-4">
        <div class="flex gap-2 justify-end mb-6">
            @auth
                @if(auth()->user()->is_admin)
                    <x-buttons.primary href="{{ route('books.edit', $book) }}">Edit</x-buttons.primary>
                @endif
            @endauth
            <x-buttons.secondary href="{{ auth()->check() && auth()->user()->is_admin ? route('books.index') : route('home') }}">Back</x-buttons.secondary>
        </div>
        <div class="mb-4">
            <x-labels.pretty value="ISBN" />
            {{ $book->isbn }}
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Name" />
            {{ $book->name }}
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Bibliography" />
            {{ $book->bibliography }}
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Publisher" />
            {{ $book->publisher->name ?? '-' }}
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Authors" />
            @foreach($book->authors as $author)
                <span class="badge">{{ $author->name }}</span>
            @endforeach
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Cover" />
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover" class="h-12">
            @else
                <span>No cover uploaded.</span>
            @endif
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Price" />
            {{ $book->price ? number_format($book->price, 2) : '-' }}
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Copies" />
            {{ $book->copies }}
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Times Requested" />
            {{ $book->requisitions_count }}
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Average Rating" />
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
        @endauth


        <!-- Reviews Section -->
        <div class="mt-8">
            <x-labels.pretty value="Reviews" />
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

        <!-- Related Books Section -->
        @if($relatedBooks->count() > 0)
            <div class="mt-8">
                <x-labels.pretty value="Related Books You Might Like" />
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($relatedBooks as $related)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                            <div class="flex gap-4">
                                @if($related['book']->cover_image)
                                    <img src="{{ asset('storage/' . $related['book']->cover_image) }}"
                                         alt="{{ $related['book']->name }}"
                                         class="w-16 h-24 object-cover rounded">
                                @else
                                    <div class="w-16 h-24 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">
                                        No Cover
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <a href="{{ route('books.show', $related['book']) }}"
                                       class="font-semibold text-blue-600 hover:underline line-clamp-2">
                                        {{ $related['book']->name }}
                                    </a>
                                    <p class="text-xs text-gray-600 mt-1">
                                        @if($related['book']->authors->count() > 0)
                                            by {{ $related['book']->authors->pluck('name')->join(', ') }}
                                        @endif
                                    </p>
                                    <div class="mt-2">
                                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded">
                                            {{ $related['similarity_percentage'] }}% Match
                                        </span>
                                    </div>
                                    @if($related['book']->copies > 0)
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mt-1">
                                            Available
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layout>
