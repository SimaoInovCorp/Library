@props([
    'type' => 'text',
    'name',
    'id' => null,
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'class' => ''
])

<input
    type="{{ $type }}"
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    value="{{ old($name, $value) }}"
    @if($required) required @endif
    placeholder="{{ $placeholder }}"
    class="form-input w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out {{ $class }}"
/>
