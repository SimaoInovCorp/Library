@props([
    'name',
    'id' => null,
    'required' => false,
    'class' => ''
])

<input
    type="file"
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    @if($required) required @endif
    class="form-input w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out {{ $class }}"
/>