<x-layout>
    <x-slot name="heading">User Details</x-slot>
    <div class="container mx-auto py-4">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <p class="text-gray-900">{{ $user->name }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <p class="text-gray-900">{{ $user->email }}</p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                <p class="text-gray-900">
                    @if($user->is_admin)
                        <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">Admin</span>
                    @else
                        <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">User</span>
                    @endif
                </p>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Member Since</label>
                <p class="text-gray-900">{{ $user->created_at->format('F d, Y') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">Edit</a>
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Back to List</a>
            </div>
        </div>
    </div>
</x-layout>
