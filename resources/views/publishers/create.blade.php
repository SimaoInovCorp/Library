<x-layout>
    <x-slot name="header">
        Add Publisher
    </x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('publishers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block">Name</label>
                <input type="text" name="name" id="name" class="form-input w-full" value="{{ old('name') }}" required>
                @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="logo" class="block">Logo</label>
                <input type="file" name="logo" id="logo" class="form-input w-full">
                @error('logo')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <x-buttons.primary type="submit">Create</x-buttons.primary>
            <x-buttons.secondary href="{{ route('publishers.index') }}">Cancel</x-buttons.secondary>
        </form>
    </div>
</x-layout>
