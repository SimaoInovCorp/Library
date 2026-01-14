<x-layout>
    <x-slot name="heading">Books</x-slot>
    <div class="container mx-auto py-4">
        <div class="flex justify-between mb-4">
            <a href="{{ route('books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Add Book</a>
            <a href="{{ route('books.export.csv') }}" class="btn btn-secondary px-4 py-2 rounded">Export CSV</a>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">ISBN</th>
                    <th class="px-4 py-2 text-left">
                        @php
                            $isSorted = (isset($sort) && $sort === 'name');
                            $nextDirection = ($isSorted && isset($direction) && $direction === 'asc') ? 'desc' : 'asc';
                        @endphp
                        <a href="{{ route('books.index', ['sort' => 'name', 'direction' => $nextDirection]) }}" class="hover:underline flex items-center gap-1">
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
                    <th class="px-4 py-2 text-left">Publisher</th>
                    <th class="px-4 py-2 text-left">Authors</th>
                    <th class="px-4 py-2 text-left">Cover</th>
                    <th class="px-4 py-2 text-left">Price</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                    <tr>
                        <td class="px-4 py-2 align-middle">{{ $book->isbn }}</td>
                        <td class="px-4 py-2 align-middle">{{ $book->name }}</td>
                        <td class="px-4 py-2 align-middle">{{ $book->publisher->name ?? '-' }}</td>
                        <td class="px-4 py-2 align-middle">
                            @foreach($book->authors as $author)
                                <span class="badge">{{ $author->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-2 align-middle">
                            <x-image-display :src="$book->cover_image" alt="Cover" />
                        </td>
                        <td class="px-4 py-2 align-middle">{{ $book->price ? number_format($book->price, 2) : '-' }}</td>
                        <td class="px-4 py-2 align-middle">
                            <a href="{{ route('books.show', $book) }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-2 py-1 rounded">View</a>
                            <a href="{{ route('books.edit', $book) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded">Edit</a>
                            <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <x-danger-button onclick="return confirm('Are you sure?')">Delete</x-danger-button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $books->appends(request()->except('page'))->links() }}</div>
    </div>
</x-layout>
