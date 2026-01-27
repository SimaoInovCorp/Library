<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-2 py-1 rounded shadow-lg font-bold transition duration-200 ease-in-out transform hover:scale-105 bg-red-600 hover:bg-red-700 text-white']) }}>
    {{ $slot }}
</button>
