@props(['href' => null])

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-2 py-1 rounded shadow-lg font-bold transition duration-200 ease-in-out transform hover:scale-105 bg-blue-600 text-white hover:bg-blue-700']) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-2 py-1 rounded shadow-lg font-bold transition duration-200 ease-in-out transform hover:scale-105 bg-blue-600 text-white hover:bg-blue-700']) }}>
        {{ $slot }}
    </button>
@endif
