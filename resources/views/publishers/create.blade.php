<x-layout>
    <x-slot name="heading">Add Publisher</x-slot>
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
            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Create</button>
            <a href="{{ route('publishers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
        </form>
    </div>
</x-layout>
