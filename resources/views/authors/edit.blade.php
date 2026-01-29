<x-layout>
    <x-slot name="header">
        Edit Author
    </x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('authors.update', $author) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block">Name</label>
                <input type="text" name="name" id="name" class="form-input w-full" value="{{ old('name', $author->name) }}" required>
                @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="picture" class="block">Picture</label>
                <input type="file" name="picture" id="picture" class="form-input w-full">
                @if($author->picture)
                    <img src="{{ asset('storage/' . $author->picture) }}" alt="Picture" class="h-8 mt-2">
                @endif
                @error('picture')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Update</button>
            <a href="{{ route('authors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
        </form>
    </div>
</x-layout>
