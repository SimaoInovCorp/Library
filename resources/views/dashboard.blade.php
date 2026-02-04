<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Welcome to Your Dashboard</h3>
                <p class="mb-6 text-gray-600">Manage your account and settings from here.</p>
                <div class="flex gap-4">
                    <a href="{{ route('home') }}" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded shadow-md inline-block transition duration-150 border border-black">
                        Go to Home
                    </a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('users.index') }}" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded shadow-md inline-block transition duration-150 border border-black">
                            Manage Users
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="bg-yellow-400 text-black font-bold px-6 py-3 rounded shadow-md inline-block transition duration-150 border border-black">
                            Moderate Reviews
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
