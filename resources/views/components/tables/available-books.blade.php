@props(['books'])

<table class="table-auto w-full border-gray-200 border shadow mb-4">
    <thead>
        <tr>
            <th class="px-4 py-2 text-left bg-blue-100">ISBN</th>
            <th class="px-4 py-2 text-left bg-blue-100">Name</th>
            <th class="px-4 py-2 text-left bg-blue-100">Publisher</th>
            <th class="px-4 py-2 text-left bg-blue-100">Authors</th>
            <th class="px-4 py-2 text-left bg-blue-100">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($books as $book)
            <tr class="even:bg-blue-50 hover:bg-blue-100">
                <td class="px-4 py-2 align-middle">{{ $book->isbn }}</td>
                <td class="px-4 py-2 align-middle">{{ $book->name }}</td>
                <td class="px-4 py-2 align-middle">{{ $book->publisher->name ?? '-' }}</td>
                <td class="px-4 py-2 align-middle">
                    @foreach($book->authors as $author)
                        <span class="badge">{{ $author->name }}</span>
                    @endforeach
                </td>
                <td class="px-4 py-2 align-middle">
                    <form action="{{ route('books.requisitions.store', $book) }}" method="POST" class="inline">
                        @csrf
                        <x-buttons.request>Request Loan</x-buttons.request>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>