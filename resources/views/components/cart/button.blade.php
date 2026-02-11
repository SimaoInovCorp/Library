@props([
    'count' => 0,
    'link' => route('cart.index'),
])
<a href="{{ $link }}" class="relative inline-flex items-center px-3 py-2 rounded-lg text-gray-700 hover:text-blue-700 hover:bg-blue-50 transition group">
    <svg class="w-6 h-6 mr-1 text-gray-500 group-hover:text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 007.5 19h9a2 2 0 001.85-1.3L17 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7" />
    </svg>
    <span class="font-semibold">Cart</span>
    @if($count > 0)
        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full px-1.5 py-0.5 min-w-[1.5em] text-center animate-pulse">
            {{ $count }}
        </span>
    @endif
</a>