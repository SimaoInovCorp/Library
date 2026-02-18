<x-layout>
    <x-slot name="header">
        Books
    </x-slot>
    <div class="container mx-auto py-4">
        <div class="flex justify-between mb-4">
            <div class="flex gap-2">
                <x-buttons.link :href="route('books.create')">Add Book</x-buttons.link>
                <x-buttons.link :href="route('books.import.google')" class="bg-green-600 hover:bg-green-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Import from Google Books
                </x-buttons.link>
            </div>
            <x-buttons.link :href="route('books.export.csv')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Export CSV
            </x-buttons.link>
        </div>
        <form method="GET" action="{{ route('books.index') }}" class="mb-4 flex flex-row gap-2">
            <x-inputs.text
                name="search"
                :value="request('search')"
                placeholder="Search by name or ISBN"
                class="px-4 py-2"
            />
            <x-buttons.search>Search</x-buttons.search>
            @if(request('search'))
                <x-buttons.clear :href="route('books.index', array_filter(request()->except(['search', 'page'])))">Clear</x-buttons.clear>
            @endif
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="direction" value="{{ $direction }}">
        </form>
        @if(session('success'))
            <x-toast type="success">{{ session('success') }}</x-toast>
        @endif
        <x-tables.books-admin :books="$books" :sort="$sort" :direction="$direction" />
        <div class="mt-4">{{ $books->appends(request()->except('page'))->links() }}</div>
    </div>
</x-layout>
