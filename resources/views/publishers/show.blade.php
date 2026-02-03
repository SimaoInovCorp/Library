<x-layout>
    <x-slot name="header">
        Publisher Details
    </x-slot>
    <div class="container mx-auto py-4">
        <div class="flex gap-2 justify-end mb-6">
            <x-buttons.primary href="{{ route('publishers.edit', $publisher) }}">Edit</x-buttons.primary>
            <x-buttons.secondary href="{{ route('publishers.index') }}">Back</x-buttons.secondary>
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Name" />
            {{ $publisher->name }}
        </div>
        <div class="mb-4">
            <x-labels.pretty value="Logo" />
            @if($publisher->logo)
                <img src="{{ asset('storage/' . $publisher->logo) }}" alt="Logo" class="h-12">
            @else
                <span>No logo uploaded.</span>
            @endif
        </div>
    </div>
</x-layout>
