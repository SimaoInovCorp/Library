<div x-data="{ show: true }" x-show="show" class="fixed top-4 right-4 z-50">
    <div class="bg-green-500 text-white px-4 py-2 rounded shadow-lg flex items-center">
        <span>{{ $slot }}</span>
        <button type="button" class="ml-4 text-white" @click="show = false">&times;</button>
    </div>
</div>
