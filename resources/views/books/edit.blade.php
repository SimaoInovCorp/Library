<x-layout>
    <x-slot name="header">
        Edit Book
    </x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
            <div class="flex gap-2 justify-end mb-6">
                <x-buttons.primary type="submit">Update</x-buttons.primary>
                <x-buttons.secondary href="{{ route('books.index') }}">Cancel</x-buttons.secondary>
            </div>
            @csrf
            @method('PUT')
            <div class="mb-4">
                <x-labels.pretty for="isbn" value="ISBN" />
                <x-inputs.text name="isbn" :value="old('isbn', $book->isbn)" required />
                @error('isbn')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-labels.pretty for="name" value="Name" />
                <x-inputs.text name="name" :value="old('name', $book->name)" required />
                @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-labels.pretty for="bibliography" value="Bibliography" />
                <x-inputs.textarea name="bibliography" :id="'bibliography'">{{ old('bibliography', $book->bibliography) }}</x-inputs.textarea>
                @error('bibliography')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-labels.pretty for="cover_image" value="Cover Image" />
                <x-inputs.file name="cover_image" />
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Cover" class="h-8 mt-2">
                @endif
                @error('cover_image')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-labels.pretty for="price" value="Price" />
                <x-inputs.number name="price" step="0.01" :value="old('price', $book->price)" />
                @error('price')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-labels.pretty for="copies" value="Copies" />
                <x-inputs.number name="copies" :value="old('copies', $book->copies)" min="0" required />
                @error('copies')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-labels.pretty for="publisher_id" value="Publisher" />
                <select name="publisher_id" id="publisher_id" class="form-input w-full" required>
                    <option value="">Select Publisher</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}" @selected(old('publisher_id', $book->publisher_id) == $publisher->id)>{{ $publisher->name }}</option>
                    @endforeach
                </select>
                @error('publisher_id')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-labels.pretty for="authors" value="Authors" />
                <select name="authors[]" id="authors" class="form-input w-full" multiple required>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" @selected(collect(old('authors', $book->authors->pluck('id')))->contains($author->id))>{{ $author->name }}</option>
                    @endforeach
                </select>
                @error('authors')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
        </form>
    </div>
</x-layout>
