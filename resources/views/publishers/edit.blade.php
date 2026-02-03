<x-layout>
    <x-slot name="header">
        Edit Publisher
    </x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('publishers.update', $publisher) }}" method="POST" enctype="multipart/form-data">
            <div class="flex gap-2 justify-end mb-6">
                <x-buttons.primary type="submit">Update</x-buttons.primary>
                <x-buttons.secondary href="{{ route('publishers.index') }}">Cancel</x-buttons.secondary>
            </div>
            </div>
            @csrf
            @method('PUT')
            <div class="mb-4">
                <x-labels.pretty for="name" value="Name" />
                <x-inputs.text name="name" :value="old('name', $publisher->name)" required />
                @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <x-labels.pretty for="logo" value="Logo" />
                <x-inputs.file name="logo" />
                @if($publisher->logo)
                    <img src="{{ asset('storage/' . $publisher->logo) }}" alt="Logo" class="h-8 mt-2">
                @endif
                @error('logo')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
        </form>
    </div>
</x-layout>
