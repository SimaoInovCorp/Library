<x-layout>
    <x-slot name="header">
        Edit User
    </x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            @include('users.partials.form', ['submitLabel' => 'Update User', 'user' => $user])
        </form>
    </div>
</x-layout>
