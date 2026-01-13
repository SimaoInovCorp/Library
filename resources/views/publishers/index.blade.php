<x-layout>
    <x-slot name="heading">Publishers</x-slot>
    <div class="container mx-auto py-4">
        <a href="{{ route('publishers.create') }}" class="btn btn-primary mb-4">Add Publisher</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Logo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($publishers as $publisher)
                    <tr>
                        <td>{{ $publisher->name }}</td>
                        <td>
                            @if($publisher->logo)
                                <img src="{{ asset('storage/' . $publisher->logo) }}" alt="Logo" class="h-8">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('publishers.show', $publisher) }}" class="btn btn-info">View</a>
                            <a href="{{ route('publishers.edit', $publisher) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('publishers.destroy', $publisher) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $publishers->links() }}</div>
    </div>
</x-layout>
