@props([
    'active' => false,
    'color' => 'gray', // yellow, green, red, gray
])

@php
    $base = 'px-4 py-2 rounded-lg font-semibold transition-colors';
    $activeColors = [
        'yellow' => 'bg-yellow-500 text-white',
        'green' => 'bg-green-500 text-white',
        'red' => 'bg-red-500 text-white',
        'gray' => 'bg-gray-200 text-gray-700',
    ];
    $inactiveColors = [
        'yellow' => 'bg-gray-200 text-gray-700 hover:bg-gray-300',
        'green' => 'bg-gray-200 text-gray-700 hover:bg-gray-300',
        'red' => 'bg-gray-200 text-gray-700 hover:bg-gray-300',
        'gray' => 'bg-gray-200 text-gray-700 hover:bg-gray-300',
    ];
    $classes = $base . ' ' . ($active ? $activeColors[$color] : $inactiveColors[$color]);
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
