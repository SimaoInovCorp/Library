<x-layout>
    <x-slot name="heading">Edit Book</x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="isbn" class="block">ISBN</label>
                <input type="text" name="isbn" id="isbn" class="form-input w-full" value="{{ old('isbn', $book->isbn) }}" required>
                @error('isbn')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="name" class="block">Name</label>
                <input type="text" name="name" id="name" class="form-input w-full" value="{{ old('name', $book->name) }}" required>
                @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="bibliography" class="block">Bibliography</label>
                <textarea name="bibliography" id="bibliography" class="form-input w-full">{{ old('bibliography', $book->bibliography) }}</textarea>
                @error('bibliography')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="cover_image" class="block">Cover Image</label>
                <input type="file" name="cover_image" id="cover_image" class="form-input w-full">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover" class="h-8 mt-2">
                @endif
                @error('cover_image')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="price" class="block">Price</label>
                <input type="number" step="0.01" name="price" id="price" class="form-input w-full" value="{{ old('price', $book->price) }}">
                @error('price')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="copies" class="block">Copies</label>
                <input type="number" name="copies" id="copies" class="form-input w-full" value="{{ old('copies', $book->copies) }}" min="0" required>
                @error('copies')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="publisher_id" class="block">Publisher</label>
                <select name="publisher_id" id="publisher_id" class="form-input w-full" required>
                    <option value="">Select Publisher</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}" @selected(old('publisher_id', $book->publisher_id) == $publisher->id)>{{ $publisher->name }}</option>
                    @endforeach
                </select>
                @error('publisher_id')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="authors" class="block">Authors</label>
                <select name="authors[]" id="authors" class="form-input w-full" multiple required>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" @selected(collect(old('authors', $book->authors->pluck('id')))->contains($author->id))>{{ $author->name }}</option>
                    @endforeach
                </select>
                @error('authors')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
        </form>
    </div>
</x-layout>
