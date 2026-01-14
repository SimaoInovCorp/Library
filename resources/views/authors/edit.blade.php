<x-layout>
    <x-slot name="heading">Edit Author</x-slot>
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
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('authors.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-layout>
