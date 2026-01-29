<x-layout>
    <x-slot name="header">
        Add Book
    </x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="isbn" class="block">ISBN</label>
                <input type="text" name="isbn" id="isbn" class="form-input w-full" value="{{ old('isbn') }}" required>
                @error('isbn')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="name" class="block">Name</label>
                <input type="text" name="name" id="name" class="form-input w-full" value="{{ old('name') }}" required>
                @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="bibliography" class="block">Bibliography</label>
                <textarea name="bibliography" id="bibliography" class="form-input w-full">{{ old('bibliography') }}</textarea>
                @error('bibliography')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="cover_image" class="block">Cover Image</label>
                <input type="file" name="cover_image" id="cover_image" class="form-input w-full">
                @error('cover_image')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="price" class="block">Price</label>
                <input type="number" step="0.01" name="price" id="price" class="form-input w-full" value="{{ old('price') }}">
                @error('price')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="copies" class="block">Copies</label>
                <input type="number" name="copies" id="copies" class="form-input w-full" value="{{ old('copies', 1) }}" min="0" required>
                @error('copies')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="publisher_id" class="block">Publisher</label>
                <select name="publisher_id" id="publisher_id" class="form-input w-full" required>
                    <option value="">Select Publisher</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}" @selected(old('publisher_id') == $publisher->id)>{{ $publisher->name }}</option>
                    @endforeach
                </select>
                @error('publisher_id')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="authors" class="block">Authors</label>
                <select name="authors[]" id="authors" class="form-input w-full" multiple required>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" @selected(collect(old('authors'))->contains($author->id))>{{ $author->name }}</option>
                    @endforeach
                </select>
                @error('authors')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <x-buttons.primary type="submit">Create</x-buttons.primary>
            <x-buttons.secondary href="{{ route('books.index') }}">Cancel</x-buttons.secondary>
        </form>
    </div>
</x-layout>
