@props([
    'name',
    'id' => null,
    'rows' => 3,
    'required' => false,
    'class' => ''
])

<textarea
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    rows="{{ $rows }}"
    @if($required) required @endif
    class="form-textarea w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out {{ $class }}"
>{{ $slot }}</textarea>