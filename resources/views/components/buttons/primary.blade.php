<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 rounded shadow-lg font-bold transition duration-200 ease-in-out transform hover:scale-105 bg-blue-600 hover:bg-blue-700 text-white']) }}>
    {{ $slot }}
</button>
