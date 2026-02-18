{{--
    Clear Button Component
    Usage: <x-buttons.clear :href="route('home')">Clear</x-buttons.clear>
    Optional props: class
--}}
@props([
    'href',
    'class' => ''
])
<a href="{{ $href }}" {{ $attributes->merge([
    'class' => 'bg-gray-300 text-gray-800 px-4 py-2 rounded transition duration-150 hover:bg-gray-400 ' . $class
]) }}>
    {{ $slot ?? 'Clear' }}
</a>
