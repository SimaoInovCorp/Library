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
        @if(isset($popularBooks) && $popularBooks->count())
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">🔥 Most Popular Books</h2>
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($popularBooks as $book)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm text-gray-500">{{ $loop->iteration }}.</p>
                                    <a href="{{ route('books.show', $book) }}" class="text-lg font-semibold text-blue-700 hover:underline">{{ $book->name }}</a>
                                    <p class="text-sm text-gray-600">{{ $book->authors->pluck('name')->join(', ') }}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ $book->requisitions_count }} requests</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <!-- Featured Reviews Section -->
        @if($featuredReviews->count() > 0)
            <div class="mb-8 bg-blue-50 border border-blue-200 rounded p-6">
                <x-sections.header>📚 Featured Reviews</x-sections.header>
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

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <x-sections.header>Available Books</x-sections.header>
            <form method="GET" action="{{ route('home') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or ISBN" class="form-input px-4 py-2 rounded border border-gray-300" />
                <x-buttons.search>Search</x-buttons.search>
                @if($search)
                    <a href="{{ route('home') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">Clear</a>
                @endif
            </form>
        </div>
        @if(isset($availableBooks) && $availableBooks->count())
            <x-tables.books :books="$availableBooks" />
            {{ $availableBooks->links() }}
        @else
            <p class="text-gray-600">No available books at the moment.</p>
        @endif
    </div>
</x-layout>