@props(['books'])

<table class="table-auto w-full border-gray-200 border shadow">
    <thead>
        <tr>
            <th class="px-4 py-2 text-left bg-blue-100">Name</th>
            <th class="px-4 py-2 text-left bg-blue-100">Authors</th>
            <th class="px-4 py-2 text-left bg-blue-100">Copies</th>
            <th class="px-4 py-2 text-left bg-blue-100">Status</th>
            <th class="px-4 py-2 text-left bg-blue-100">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($books as $book)
            <tr class="even:bg-blue-50 hover:bg-blue-100">
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
                            <x-buttons.request>Request</x-buttons.request>
                        </form>
                    @endauth
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
