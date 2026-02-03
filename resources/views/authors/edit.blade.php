<x-layout>
    <x-slot name="header">
        Edit Author
    </x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('authors.update', $author) }}" method="POST" enctype="multipart/form-data">
            <div class="flex gap-2 justify-end mb-6">
                <x-buttons.primary type="submit">Update</x-buttons.primary>
                <x-buttons.secondary href="{{ route('authors.index') }}">Cancel</x-buttons.secondary>
            </div>
            </div>
            @csrf
            @method('PUT')
            <div class="mb-4">
                <x-labels.pretty for="name" value="Name" />
                <x-inputs.text name="name" :value="old('name', $author->name)" required />
                @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-labels.pretty for="picture" value="Picture" />
                <x-inputs.file name="picture" />
                @if($author->picture)
                    <img src="{{ asset('storage/' . $author->picture) }}" alt="Picture" class="h-8 mt-2">
                @endif
                @error('picture')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
        </form>
    </div>
</x-layout>
