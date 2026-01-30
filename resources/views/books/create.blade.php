<x-layout>
    <x-slot name="header">
        Add Book
    </x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <x-label for="isbn" value="ISBN" />
                <x-inputs.text name="isbn" :value="old('isbn')" required />
                @error('isbn')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-label for="name" value="Name" />
                <x-inputs.text name="name" :value="old('name')" required />
                @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-label for="bibliography" value="Bibliography" />
                <x-inputs.textarea name="bibliography" :id="'bibliography'">{{ old('bibliography') }}</x-inputs.textarea>
                @error('bibliography')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-label for="cover_image" value="Cover Image" />
                <x-inputs.file name="cover_image" />
                @error('cover_image')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-label for="price" value="Price" />
                <x-inputs.number name="price" step="0.01" :value="old('price')" />
                @error('price')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-label for="copies" value="Copies" />
                <x-inputs.number name="copies" :value="old('copies', 1)" min="0" required />
                @error('copies')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-label for="publisher_id" value="Publisher" />
                <select name="publisher_id" id="publisher_id" class="form-input w-full" required>
                    <option value="">Select Publisher</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}" @selected(old('publisher_id') == $publisher->id)>{{ $publisher->name }}</option>
                    @endforeach
                </select>
                @error('publisher_id')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-label for="authors" value="Authors" />
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
