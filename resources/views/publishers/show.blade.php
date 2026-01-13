<x-layout>
    <x-slot name="heading">Publisher Details</x-slot>
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
        <a href="{{ route('publishers.edit', $publisher) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('publishers.index') }}" class="btn btn-secondary">Back</a>
    </div>
</x-layout>
