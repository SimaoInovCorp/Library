<x-layout>
    <x-slot name="heading">Author Details</x-slot>
    <div class="container mx-auto py-4">
        <div class="mb-4">
            <strong>Name:</strong> {{ $author->name }}
        </div>
        <div class="mb-4">
            <strong>Picture:</strong>
            @if($author->picture)
                <img src="{{ asset('storage/' . $author->picture) }}" alt="Picture" class="h-12">
            @else
                <span>No picture uploaded.</span>
            @endif
        </div>
        <a href="{{ route('authors.edit', $author) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('authors.index') }}" class="btn btn-secondary">Back</a>
    </div>
</x-layout>
