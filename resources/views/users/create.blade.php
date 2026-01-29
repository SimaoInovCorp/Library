<x-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Add User</h1>
    </x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            @include('users.partials.form', ['submitLabel' => 'Create User'])
        </form>
    </div>
</x-layout>
