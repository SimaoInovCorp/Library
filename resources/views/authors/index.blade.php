<x-layout>
    <x-slot name="header">
        Authors
    </x-slot>
    <div class="container mx-auto py-4">
        <a href="{{ route('authors.create') }}" class="inline-block mb-4"><x-buttons.primary>Add Author</x-buttons.primary></a>
        <form method="GET" action="{{ route('authors.index') }}" class="mb-4 flex flex-row gap-2">
            <x-forms.search
                :action="route('authors.index')"
                :value="request('search')"
                placeholder="Search by name"
            >
                @if(request('search'))
                    <x-buttons.clear :href="route('authors.index', array_filter(request()->except(['search', 'page'])))">Clear</x-buttons.clear>
                @endif
                <input type="hidden" name="sort" value="{{ $sort }}">
                <input type="hidden" name="direction" value="{{ $direction }}">
            </x-forms.search>
        </form>
        @if(session('success'))
            <x-toast type="success">{{ session('success') }}</x-toast>
        @endif
        <x-tables.authors :authors="$authors" :sort="$sort" :direction="$direction" />
        <div class="mt-4">{{ $authors->appends(request()->except('page'))->links() }}</div>
    </div>
</x-layout>
