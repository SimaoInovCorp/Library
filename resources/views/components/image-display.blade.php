@props(['src', 'alt' => '', 'class' => 'h-8'])

@if($src)
    @php
        $isExternal = Str::startsWith($src, ['http://', 'https://']);
    @endphp
    <img src="{{ $isExternal ? $src : asset('storage/' . $src) }}" alt="{{ $alt }}" class="{{ $class }}">
@else
    <span class="text-gray-400">No image</span>
@endif
