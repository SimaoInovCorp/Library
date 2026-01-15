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
        <a href="{{ route('authors.edit', $author) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Edit</a>
        <a href="{{ route('authors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Back</a>
    </div>
</x-layout>
