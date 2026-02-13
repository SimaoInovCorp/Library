@props(['activeCount', 'last30DaysCount', 'returnedTodayCount'])

<div class="flex flex-wrap gap-4 mb-8">
    <div class="bg-blue-100 border border-blue-400 text-blue-800 px-6 py-4 rounded shadow">
        <div class="font-bold text-lg">Active requisitions</div>
        <div class="text-2xl">{{ $activeCount }}</div>
    </div>
    <div class="bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded shadow">
        <div class="font-bold text-lg">Requisitions in the last 30 days</div>
        <div class="text-2xl">{{ $last30DaysCount }}</div>
    </div>
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-6 py-4 rounded shadow">
        <div class="font-bold text-lg">Books returned today</div>
        <div class="text-2xl">{{ $returnedTodayCount }}</div>
    </div>
</div>