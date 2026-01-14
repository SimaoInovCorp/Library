<x-layout>
    <x-slot name="heading">Add Author</x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('authors.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block">Name</label>
                <input type="text" name="name" id="name" class="form-input w-full" value="{{ old('name') }}" required>
                @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="picture" class="block">Picture</label>
                <input type="file" name="picture" id="picture" class="form-input w-full">
                @error('picture')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <x-button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white">Create</x-button>
            <a href="{{ route('authors.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
        </form>
    </div>
</x-layout>
