<x-layout>
    <x-slot name="heading">Authors</x-slot>
    <div class="container mx-auto py-4">
        <a href="{{ route('authors.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded mb-4 inline-block">Add Author</a>
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
                        <a href="{{ route('authors.index', ['sort' => 'name', 'direction' => $nextDirection]) }}" class="hover:underline flex items-center gap-1">
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
                    <th class="px-4 py-2 text-left">Picture</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($authors as $author)
                    <tr>
                        <td class="px-4 py-2 align-middle">{{ $author->name }}</td>
                        <td class="px-4 py-2 align-middle">
                            <x-image-display :src="$author->picture" alt="Picture" />
                        </td>
                        <td class="px-4 py-2 align-middle">
                            <a href="{{ route('authors.show', $author) }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-2 py-1 rounded">View</a>
                            <a href="{{ route('authors.edit', $author) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">Edit</a>
                            <form action="{{ route('authors.destroy', $author) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <x-danger-button onclick="return confirm('Are you sure?')">Delete</x-danger-button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $authors->appends(request()->except('page'))->links() }}</div>
    </div>
</x-layout>
