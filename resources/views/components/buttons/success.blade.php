<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 rounded shadow-lg font-bold transition duration-200 ease-in-out transform hover:scale-105 bg-green-600 hover:bg-green-700 text-white']) }}>
    {{ $slot }}
</button>
