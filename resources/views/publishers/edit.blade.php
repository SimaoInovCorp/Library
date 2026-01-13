<x-layout>
    <x-slot name="heading">Edit Publisher</x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('publishers.update', $publisher) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block">Name</label>
                <input type="text" name="name" id="name" class="form-input w-full" value="{{ old('name', $publisher->name) }}" required>
                @error('name')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label for="logo" class="block">Logo</label>
                <input type="file" name="logo" id="logo" class="form-input w-full">
                @if($publisher->logo)
                    <img src="{{ asset('storage/' . $publisher->logo) }}" alt="Logo" class="h-8 mt-2">
                @endif
                @error('logo')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('publishers.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</x-layout>
