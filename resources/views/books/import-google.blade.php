<x-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Import Books from Google Books</h1>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-8">
                <h2 class="text-3xl font-bold text-white">Import Books from Google Books</h2>
                <p class="mt-2 text-blue-100">Search and import books directly from Google's extensive catalog</p>
            </div>

            <div class="p-6">
                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Search Form -->
                <div class="mb-8">
                    <form method="GET" action="{{ route('books.import.google.search') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-3">
                                <label for="query" class="block text-sm font-medium text-gray-700 mb-2">
                                    Search Query
                                </label>
                                <input
                                    type="text"
                                    id="query"
                                    name="query"
                                    value="{{ $searchQuery ?? '' }}"
                                    placeholder="Enter title, author, ISBN, or keywords..."
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button
                                type="submit"
                                class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                            >
                                Search Books
                            </button>

                            @if(isset($searchResults))
                                <a
                                    href="{{ route('books.import.google') }}"
                                    class="px-6 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors"
                                >
                                    Clear Results
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Search Results -->
                @if(isset($searchResults) && count($searchResults) > 0)
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">
                            Search Results ({{ count($searchResults) }} books found)
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($searchResults as $book)
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                    <!-- Book Cover -->
                                    <div class="h-64 bg-gray-100 flex items-center justify-center">
                                        @if($book['thumbnail_url'])
                                            <img
                                                src="{{ $book['thumbnail_url'] }}"
                                                alt="{{ $book['title'] }}"
                                                class="max-h-full max-w-full object-contain"
                                            >
                                        @else
                                            <svg class="h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Book Details -->
                                    <div class="p-4">
                                        <h4 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2" title="{{ $book['title'] }}">
                                            {{ $book['title'] }}
                                        </h4>

                                        <div class="text-sm text-gray-600 space-y-1 mb-3">
                                            @if(!empty($book['authors']))
                                                <p class="line-clamp-1">
                                                    <span class="font-semibold">Authors:</span>
                                                    {{ implode(', ', $book['authors']) }}
                                                </p>
                                            @endif

                                            @if($book['publisher'])
                                                <p class="line-clamp-1">
                                                    <span class="font-semibold">Publisher:</span>
                                                    {{ $book['publisher'] }}
                                                </p>
                                            @endif

                                            @if($book['isbn'])
                                                <p>
                                                    <span class="font-semibold">ISBN:</span>
                                                    {{ $book['isbn'] }}
                                                </p>
                                            @endif

                                            @if($book['price'])
                                                <p>
                                                    <span class="font-semibold">Price:</span>
                                                    {{ $book['currency_code'] ?? '' }} {{ number_format($book['price'], 2) }}
                                                </p>
                                            @endif
                                        </div>

                                        @if($book['description'])
                                            <p class="text-sm text-gray-500 line-clamp-3 mb-4">
                                                {{ $book['description'] }}
                                            </p>
                                        @endif

                                        <!-- Import Form -->
                                        <form method="POST" action="{{ route('books.import.google.import') }}" class="space-y-3">
                                            @csrf
                                            <input type="hidden" name="volume_id" value="{{ $book['volume_id'] }}">
                                            <input type="hidden" name="isbn" value="{{ $book['isbn'] }}">
                                            <input type="hidden" name="title" value="{{ $book['title'] }}">
                                            <input type="hidden" name="description" value="{{ $book['description'] }}">
                                            @if(!empty($book['authors']))
                                                @foreach($book['authors'] as $author)
                                                    <input type="hidden" name="authors[]" value="{{ $author }}">
                                                @endforeach
                                            @endif
                                            <input type="hidden" name="publisher" value="{{ $book['publisher'] }}">
                                            <input type="hidden" name="published_date" value="{{ $book['published_date'] }}">
                                            <input type="hidden" name="thumbnail_url" value="{{ $book['thumbnail_url'] }}">
                                            <input type="hidden" name="price" value="{{ $book['price'] }}">
                                            <input type="hidden" name="currency_code" value="{{ $book['currency_code'] }}">
                                            <input type="hidden" name="page_count" value="{{ $book['page_count'] }}">
                                            <input type="hidden" name="language" value="{{ $book['language'] }}">

                                            <div>
                                                <label for="copies_{{ $book['volume_id'] }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Copies
                                                </label>
                                                <input
                                                    type="number"
                                                    id="copies_{{ $book['volume_id'] }}"
                                                    name="copies"
                                                    value="1"
                                                    min="1"
                                                    max="5"
                                                    class="w-full px-3 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                                >
                                            </div>

                                            <button
                                                type="submit"
                                                class="w-full px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors"
                                            >
                                                Import Book
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
