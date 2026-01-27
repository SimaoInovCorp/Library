<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 rounded shadow-lg font-bold transition duration-200 ease-in-out transform hover:scale-105 bg-gray-200 text-gray-800 hover:bg-gray-300']) }}>
    {{ $slot }}
</button>
