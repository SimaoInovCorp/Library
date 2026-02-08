<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm ' . (($disabled ?? false) ? 'opacity-50 cursor-not-allowed' : '')
    ]) }}
    @if($disabled ?? false) disabled @endif
>
    {{ $slot }}
</button>
@if(($disabled ?? false) && ($warning ?? false))
    <div class="text-red-600 text-xs mt-1">{{ $warning }}</div>
@endif
