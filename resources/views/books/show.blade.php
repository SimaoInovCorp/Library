<x-layout>
    <x-slot name="heading">Book Details</x-slot>
    <div class="container mx-auto py-4">
        <div class="mb-4">
            <strong>ISBN:</strong> {{ $book->isbn }}
        </div>
        <div class="mb-4">
            <strong>Name:</strong> {{ $book->name }}
        </div>
        <div class="mb-4">
            <strong>Bibliography:</strong> {{ $book->bibliography }}
        </div>
        <div class="mb-4">
            <strong>Publisher:</strong> {{ $book->publisher->name ?? '-' }}
        </div>
        <div class="mb-4">
            <strong>Authors:</strong>
            @foreach($book->authors as $author)
                <span class="badge">{{ $author->name }}</span>
            @endforeach
        </div>
        <div class="mb-4">
            <strong>Cover:</strong>
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover" class="h-12">
            @else
                <span>No cover uploaded.</span>
            @endif
        </div>
        <div class="mb-4">
            <strong>Price:</strong> {{ $book->price ? number_format($book->price, 2) : '-' }}
        </div>
        <a href="{{ route('books.edit', $book) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Edit</a>
        <a href="{{ route('books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Back</a>
    </div>
</x-layout>
