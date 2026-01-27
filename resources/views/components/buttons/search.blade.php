<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 rounded shadow-lg font-bold transition duration-200 ease-in-out transform hover:scale-105 bg-gray-800 text-white hover:bg-gray-900']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4-4m0 0A7 7 0 1010 17a7 7 0 007-7z" />
    </svg>
    {{ $slot }}
</button>
