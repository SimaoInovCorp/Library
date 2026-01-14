<x-layout>
    <x-slot name="heading">Authors</x-slot>
    <div class="container mx-auto py-4">
        <a href="{{ route('authors.create') }}" class="btn btn-primary mb-4">Add Author</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($authors as $author)
                    <tr>
                        <td>{{ $author->name }}</td>
                        <td>
                            @if($author->picture)
                                <img src="{{ asset('storage/' . $author->picture) }}" alt="Picture" class="h-8">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('authors.show', $author) }}" class="btn btn-info">View</a>
                            <a href="{{ route('authors.edit', $author) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('authors.destroy', $author) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $authors->links() }}</div>
    </div>
</x-layout>
