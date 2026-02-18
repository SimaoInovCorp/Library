@php
    use Illuminate\Support\Str;
@endphp

<x-layout>
    <x-slot name="header">
        Activity Logs
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">User Activity Logs</h2>
                <div class="flex gap-3 flex-wrap">
                    <x-buttons.link :href="route('admin.logs.export', array_filter(request()->query()))" class="bg-green-600 hover:bg-green-700">
                        Download CSV
                    </x-buttons.link>
                    <x-buttons.link :href="route('dashboard')" class="bg-gray-600 text-gray-500 hover:bg-gray-700">
                        Back to Dashboard
                    </x-buttons.link>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.logs.index') }}" class="grid gap-4 md:grid-cols-3 mb-6">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select id="user_id" name="user_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected($filters['user_id'] == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" id="search" name="search" value="{{ $filters['search'] }}" placeholder="Module, description, IP..." class="w-full border-gray-300 rounded-md shadow-sm" />
                </div>

                <div class="flex gap-3 md:col-span-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Apply Filters</button>
                    <a href="{{ route('admin.logs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Clear</a>
                </div>
            </form>

            <div class="overflow-x-auto">
                <x-tables.logs :logs="$logs" />
            </div>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-layout>