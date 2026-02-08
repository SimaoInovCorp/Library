<x-layout>
    <x-slot name="header">
        Authors
    </x-slot>
    <div class="container mx-auto py-4">
        <a href="{{ route('authors.create') }}" class="inline-block mb-4"><x-buttons.primary>Add Author</x-buttons.primary></a>
        <form method="GET" action="{{ route('authors.index') }}" class="mb-4 flex flex-row gap-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name"
                class="form-input px-4 py-2 rounded border border-gray-300"
            >
            <x-buttons.search>Search</x-buttons.search>
            @if(request('search'))
                <a href="{{ route('authors.index', array_filter(request()->except(['search', 'page']))) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">Clear</a>
            @endif
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="direction" value="{{ $direction }}">
        </form>
        @if(session('success'))
            <x-toast type="success">{{ session('success') }}</x-toast>
        @endif
        <x-tables.authors :authors="$authors" :sort="$sort" :direction="$direction" />
        <div class="mt-4">{{ $authors->appends(request()->except('page'))->links() }}</div>
    </div>
</x-layout>
