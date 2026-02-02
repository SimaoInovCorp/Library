<x-layout>
    <x-slot name="header">
        Home Page
    </x-slot>
    <div class="container mx-auto py-4">
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
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Authors</th>
                    <th class="px-4 py-2 text-left">Copies</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(App\Models\Book::with(['authors'])->withCount('requisitions')->get() as $book)
                    <tr>
                        <td class="px-4 py-2 align-middle">{{ $book->name }}</td>
                        <td class="px-4 py-2 align-middle">
                            @foreach($book->authors as $author)
                                <span class="badge">{{ $author->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-2 align-middle">{{ $book->copies }}</td>
                        <td class="px-4 py-2 align-middle">
                            @if($book->copies > 0)
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">Available</span>
                            @else
                                <span class="bg-gray-500 text-white px-2 py-1 rounded text-xs">Unavailable</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 align-middle">
                            <a href="{{ route('books.show', $book) }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-2 py-1 rounded">Details</a>
                            @auth
                                <form action="{{ route('books.requisitions.store', $book) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded" @if($book->copies < 1) disabled @endif>Request</button>
                                </form>
                            @endauth
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>