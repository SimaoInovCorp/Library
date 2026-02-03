@props([
    'value',
    'icon' => null,
    'color' => 'blue', // Tailwind color accent
])

<label {{ $attributes->merge([
    'class' => "inline-flex flex-col gap-1 font-bold text-base px-3 py-1 rounded-md shadow bg-{$color}-50 text-{$color}-800 border border-{$color}-200 m-1 mb-3 w-auto max-w-full"
]) }}>
    @if($icon)
        <span class="w-5 h-5 flex items-center justify-center mb-1">
            {!! $icon !!}
        </span>
    @endif
    <span>{{ $value ?? $slot }}</span>
</label>
