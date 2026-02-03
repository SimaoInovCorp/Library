<x-layout>
    <x-slot name="header">
        Author Details
    </x-slot>
    <div class="container mx-auto py-4">
        <div class="flex gap-2 justify-end mb-6">
            <x-buttons.primary href="{{ route('authors.edit', $author) }}">Edit</x-buttons.primary>
            <x-buttons.secondary href="{{ route('authors.index') }}">Back</x-buttons.secondary>
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Name" />
            {{ $author->name }}
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Picture" />
            @if($author->picture)
                <img src="{{ asset('storage/' . $author->picture) }}" alt="Picture" class="h-12">
            @else
                <span>No picture uploaded.</span>
            @endif
        </div>
    </div>
</x-layout>
