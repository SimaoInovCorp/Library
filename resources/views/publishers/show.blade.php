<x-layout>
    <x-slot name="header">
        Publisher Details
    </x-slot>
    <div class="container mx-auto py-4">
        <div class="mb-4">
            <strong>Name:</strong> {{ $publisher->name }}
        </div>
        <div class="mb-4">
            <strong>Logo:</strong>
            @if($publisher->logo)
                <img src="{{ asset('storage/' . $publisher->logo) }}" alt="Logo" class="h-12">
            @else
                <span>No logo uploaded.</span>
            @endif
        </div>
        <a href="{{ route('publishers.edit', $publisher) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Edit</a>
        <a href="{{ route('publishers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Back</a>
    </div>
</x-layout>
