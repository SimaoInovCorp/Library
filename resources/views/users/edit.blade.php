<x-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit User</h1>
    </x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            @include('users.partials.form', ['submitLabel' => 'Update User', 'user' => $user])
        </form>
    </div>
</x-layout>
