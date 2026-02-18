{{--
    Unified Search Form Component
    Usage:
    <x-forms.search :action="route('requisitions.index')" :value="$search" placeholder="Search by name or ISBN">
        @if($search)
            <a href="{{ route('requisitions.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded">Clear</a>
        @endif
    </x-forms.search>
--}}
@props([
    'action',
    'value' => '',
    'placeholder' => 'Search…',
    'name' => 'search',
    'method' => 'GET'
])
<form method="{{ $method }}" action="{{ $action }}" {{ $attributes->merge(['class' => 'flex gap-2']) }}>
    <input
        type="text"
        name="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        class="form-input px-4 py-2 rounded border border-gray-300"
    />
    <x-buttons.search>Search</x-buttons.search>
    {{ $slot }}
</form>
