@props(['src', 'alt' => '', 'class' => 'h-8'])

@if($src)
    <img src="{{ asset('storage/' . $src) }}" alt="{{ $alt }}" class="{{ $class }}">
@else
    <span class="text-gray-400">No image</span>
@endif
