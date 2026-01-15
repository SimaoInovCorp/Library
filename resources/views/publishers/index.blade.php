<x-layout>
    <x-slot name="heading">Publishers</x-slot>
    <div class="container mx-auto py-4">
        <a href="{{ route('publishers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4 inline-block">Add Publisher</a>
        <form method="GET" action="{{ route('publishers.index') }}" class="mb-4 flex flex-row gap-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name"
                class="form-input px-4 py-2 rounded border border-gray-300"
            >
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Search</button>
            @if(request('search'))
                <a href="{{ route('publishers.index', array_filter(request()->except(['search', 'page']))) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">Clear</a>
            @endif
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="direction" value="{{ $direction }}">
        </form>
        @if(session('success'))
            <x-toast type="success">{{ session('success') }}</x-toast>
        @endif
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">
                        @php
                            $isSorted = (isset($sort) && $sort === 'name');
                            $nextDirection = ($isSorted && isset($direction) && $direction === 'asc') ? 'desc' : 'asc';
                        @endphp
                        <a href="{{ route('publishers.index', ['sort' => 'name', 'direction' => $nextDirection]) }}" class="hover:underline flex items-center gap-1">
                            Name
                            @if($isSorted)
                                @if(isset($direction) && $direction === 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-2 text-left">Logo</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($publishers as $publisher)
                    <tr>
                        <td class="px-4 py-2 align-middle">{{ $publisher->name }}</td>
                        <td class="px-4 py-2 align-middle">
                            <x-image-display :src="$publisher->logo" alt="Logo" />
                        </td>
                        <td class="px-4 py-2 align-middle">
                            <div class="flex flex-row gap-2">
                                <a href="{{ route('publishers.show', $publisher) }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-2 py-1 rounded">View</a>
                                <a href="{{ route('publishers.edit', $publisher) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">Edit</a>
                                <form action="{{ route('publishers.destroy', $publisher) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button onclick="return confirm('Are you sure?')">Delete</x-danger-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $publishers->appends(request()->except('page'))->links() }}</div>
    </div>
</x-layout>
