{{--
    Section Header Component
    Usage: <x-sections.header>Section Title</x-sections.header>
    Optional props: icon, class
--}}
@props([
    'icon' => null,
    'class' => ''
])

<h2 {{ $attributes->merge([
    'class' => "text-2xl font-semibold tracking-tight text-gray-800 border-l-4 border-blue-200 pl-2 font-bold mb-4" . $class
]) }}>
    @if($icon)
        <span class="mr-2 align-middle">{!! $icon !!}</span>
    @endif
    <span class="align-middle">{{ $slot }}</span>
</h2>
