@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-base text-gray-700 bg-gray-50 px-2 py-1 rounded shadow-sm tracking-wide mb-1']) }}>
    {{ $value ?? $slot }}
</label>
