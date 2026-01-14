<x-layout>
    <x-slot name="heading">Books</x-slot>
    <div class="container mx-auto py-4">
        <div class="flex justify-between mb-4">
            <a href="{{ route('books.create') }}" class="btn btn-primary">Add Book</a>
            <a href="{{ route('books.export.csv') }}" class="btn btn-secondary">Export CSV</a>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>ISBN</th>
                    <th>Name</th>
                    <th>Publisher</th>
                    <th>Authors</th>
                    <th>Cover</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($books as $book)
                    <tr>
                        <td>{{ $book->isbn }}</td>
                        <td>{{ $book->name }}</td>
                        <td>{{ $book->publisher->name ?? '-' }}</td>
                        <td>
                            @foreach($book->authors as $author)
                                <span class="badge">{{ $author->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover" class="h-8">
                            @endif
                        </td>
                        <td>{{ $book->price ? number_format($book->price, 2) : '-' }}</td>
                        <td>
                            <a href="{{ route('books.show', $book) }}" class="btn btn-info">View</a>
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('books.destroy', $book) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $books->links() }}</div>
    </div>
</x-layout>
