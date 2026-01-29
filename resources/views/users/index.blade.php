<x-layout>
    <x-slot name="header">
        Users
    </x-slot>
    <div class="container mx-auto py-4">
        <a href="{{ route('users.create') }}" class="inline-block mb-4"><x-buttons.primary>Add User</x-buttons.primary></a>
        <form method="GET" action="{{ route('users.index') }}" class="mb-4 flex flex-row gap-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or email"
                class="form-input px-4 py-2 rounded border border-gray-300"
            >
            <x-buttons.search>Search</x-buttons.search>
            @if(request('search'))
                <a href="{{ route('users.index', array_filter(request()->except(['search', 'page']))) }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">Clear</a>
            @endif
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="direction" value="{{ $direction }}">
        </form>
        @if(session('success'))
            <x-toast type="success">{{ session('success') }}</x-toast>
        @endif
        @if(session('error'))
            <x-toast type="error">{{ session('error') }}</x-toast>
        @endif
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left">
                        @php
                            $isSorted = (isset($sort) && $sort === 'name');
                            $nextDirection = ($isSorted && isset($direction) && $direction === 'asc') ? 'desc' : 'asc';
                        @endphp
                        <a href="{{ route('users.index', ['sort' => 'name', 'direction' => $nextDirection]) }}" class="hover:underline flex items-center gap-1">
                            Name
                            @if($isSorted)
                                @if(isset($direction) && $direction === 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-2 text-left">
                        @php
                            $isSorted = (isset($sort) && $sort === 'email');
                            $nextDirection = ($isSorted && isset($direction) && $direction === 'asc') ? 'desc' : 'asc';
                        @endphp
                        <a href="{{ route('users.index', ['sort' => 'email', 'direction' => $nextDirection]) }}" class="hover:underline flex items-center gap-1">
                            Email
                            @if($isSorted)
                                @if(isset($direction) && $direction === 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                @endif
                            @endif
                        </a>
                    </th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="px-4 py-2 align-middle">{{ $user->name }}</td>
                        <td class="px-4 py-2 align-middle">{{ $user->email }}</td>
                        <td class="px-4 py-2 align-middle">
                            @if($user->is_admin)
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-xs">Admin</span>
                            @else
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-xs">User</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 align-middle">
                            <div class="flex flex-row gap-2">
                                <form method="GET" action="{{ route('users.show', $user) }}" style="display:inline;">
                                    <x-buttons.details type="submit">Details</x-buttons.details>
                                </form>
                                <form method="GET" action="{{ route('users.edit', $user) }}" style="display:inline;">
                                    <x-buttons.edit type="submit">Edit</x-buttons.edit>
                                </form>
                                @if($user->id !== auth()->id())
                                    <div x-data="{ showModal: false }">
                                        <x-buttons.danger type="button" @click="showModal = true">Delete</x-buttons.danger>
                                        <form x-ref="deleteForm" action="{{ route('users.destroy', $user) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" style="display: none;">
                                            <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
                                                <h2 class="text-lg font-semibold text-gray-900 mb-2">Delete User</h2>
                                                <p class="mb-4 text-gray-700">Are you sure you want to delete the user <span class="font-bold">{{ $user->name }}</span>? This action cannot be undone.</p>
                                                <div class="flex justify-end gap-2">
                                                    <x-buttons.secondary type="button" @click="showModal = false">Cancel</x-buttons.secondary>
                                                    <x-buttons.danger type="button" @click="$refs.deleteForm.submit(); showModal = false;">Delete</x-buttons.danger>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-layout>
