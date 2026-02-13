<x-layout>
    <x-slot name="header">
        Import Books from Google Books
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Import Books from Google Books</h2>
                    <p class="mt-2 text-gray-600">Search and import books directly from Google's extensive catalog</p>
                </div>
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
                            <x-buttons.search type="submit">
                                Search Books
                            </x-buttons.search>

                            @if(isset($searchResults))
                                <x-buttons.secondary href="{{ route('books.import.google') }}">
                                    Clear Results
                                </x-buttons.secondary>
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
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow flex flex-col">
                                        <!-- Book Cover -->
                                        <div class="h-64 bg-gray-100 flex items-center justify-center flex-shrink-0">
                                            <x-image-display :src="$book['thumbnail_url']" :alt="$book['title']" class="max-h-full max-w-full object-contain h-64 w-auto" />
                                        </div>

                                    <!-- Book Details -->
                                    <div class="p-4 flex flex-col flex-grow">
                                        <h4 class="font-bold text-lg text-gray-900 mb-2 line-clamp-2 min-h-[3.5rem]" title="{{ $book['title'] }}">
                                            {{ $book['title'] }}
                                        </h4>

                                        <div class="text-sm text-gray-600 space-y-1 mb-3">
                                            <p class="line-clamp-1 min-h-[1.25rem]">
                                                @if(!empty($book['authors']))
                                                    <span class="font-semibold">Authors:</span>
                                                    {{ implode(', ', $book['authors']) }}
                                                @else
                                                    <span class="text-gray-400">Authors: N/A</span>
                                                @endif
                                            </p>

                                            <p class="line-clamp-1 min-h-[1.25rem]">
                                                @if($book['publisher'])
                                                    <span class="font-semibold">Publisher:</span>
                                                    {{ $book['publisher'] }}
                                                @else
                                                    <span class="text-gray-400">Publisher: N/A</span>
                                                @endif
                                            </p>

                                            <p class="min-h-[1.25rem]">
                                                @if($book['isbn'])
                                                    <span class="font-semibold">ISBN:</span>
                                                    {{ $book['isbn'] }}
                                                @else
                                                    <span class="text-gray-400">ISBN: N/A</span>
                                                @endif
                                            </p>

                                            @if($book['price'])
                                                <p class="min-h-[1.25rem]">
                                                    <span class="font-semibold">Price:</span>
                                                    {{ $book['currency_code'] ?? '' }} {{ number_format($book['price'], 2) }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="mb-4 flex-grow">
                                            @if($book['description'])
                                                <p class="text-sm text-gray-500 line-clamp-3">
                                                    {{ $book['description'] }}
                                                </p>
                                            @else
                                                <p class="text-sm text-gray-400 italic">
                                                    No description available
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Import Form -->
                                        <form method="POST" action="{{ route('books.import.google.import') }}" class="space-y-3 mt-auto">
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

                                            <x-buttons.success type="submit" class="w-full">
                                                Import Book
                                            </x-buttons.success>
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
