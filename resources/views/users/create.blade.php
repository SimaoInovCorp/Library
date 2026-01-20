<x-layout>
    <x-slot name="heading">Add User</x-slot>
    <div class="container mx-auto py-4">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            @include('users.partials.form', ['submitLabel' => 'Create User'])
        </form>
    </div>
</x-layout>
