@props([
    'name',
    'id' => null,
    'value' => null,
    'min' => null,
    'max' => null,
    'step' => '1',
    'required' => false,
    'class' => ''
])

<input
    type="number"
    name="{{ $name }}"
    id="{{ $id ?? $name }}"
    value="{{ old($name, $value) }}"
    min="{{ $min }}"
    max="{{ $max }}"
    step="{{ $step }}"
    @if($required) required @endif
    class="form-input w-full rounded border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition duration-150 ease-in-out {{ $class }}"
/>
