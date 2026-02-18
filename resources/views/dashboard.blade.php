<x-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 ">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <x-sections.header>Welcome to Your Dashboard</x-sections.header>
            <p class="mb-6 text-gray-600">Manage your account and settings from here.</p>
            <div class="flex gap-4 flex-wrap">
                <a href="{{ route('home') }}" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded shadow-md inline-block transition duration-150 border border-black hover:bg-yellow-500">
                    Go to Home
                </a>
                @if(auth()->user()->is_admin)
                    <a href="{{ route('users.index') }}" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded shadow-md inline-block transition duration-150 border border-black hover:bg-yellow-500">
                        Manage Users
                    </a>
                    <a href="{{ route('admin.reviews.index') }}" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded shadow-md inline-block transition duration-150 border border-black hover:bg-yellow-500">
                        Moderate Reviews
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded shadow-md inline-block transition duration-150 border border-black hover:bg-yellow-500">
                        App Settings
                    </a>
                    <a href="{{ route('admin.logs.index') }}" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded shadow-md inline-block transition duration-150 border border-black hover:bg-yellow-500">
                        View Logs
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-layout>
