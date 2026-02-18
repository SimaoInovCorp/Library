<x-layout>
    <x-slot name="header">
        Requesitions
    </x-slot>
    <div class="container mx-auto py-4">
        <!-- Indicators Section -->
        <x-tables.indicators :active-count="$activeCount" :last-30-days-count="$last30DaysCount" :returned-today-count="$returnedTodayCount" />
        @if(session('success'))
            <x-toast type="success">{{ session('success') }}</x-toast>
        @endif
        @if(session('error'))
            <x-toast type="error">{{ session('error') }}</x-toast>
        @endif

        <!-- Available Books Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
                <x-sections.header class="mb-4">Available Books</x-sections.header>
                <form method="GET" action="{{ route('requisitions.index') }}" class="flex gap-2">
                    <x-forms.search
                        :action="route('requisitions.index')"
                        :value="$search"
                        placeholder="Search by name or ISBN"
                    >
                        @if($search)
                            <x-buttons.clear :href="route('requisitions.index')">Clear</x-buttons.clear>
                        @endif
                    </x-forms.search>
                </form>
            </div>
            @if($availableBooks->count())
                <x-tables.available-books :books="$availableBooks" />
                {{ $availableBooks->links() }}
            @else
                <p class="text-gray-600">No available books at the moment.</p>
            @endif
        </div>

        <!-- My Book Requisitions Section -->
        <div>
            <x-sections.header class="mb-4">My Book Requests</x-sections.header>
            @if($requisitions->count())
                <x-tables.requisitions :requisitions="$requisitions" />
                {{ $requisitions->links() }}
            @else
                <p class="text-gray-600">You have not made any requests yet.</p>
            @endif
        </div>

        <!-- User Activity Dashboard -->
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <x-sections.header>Your Activity</x-sections.header>
            </div>
            <div class="grid gap-6 md:grid-cols-2">
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <h3 class="text-lg font-semibold mb-3">Current Loans</h3>
                    @if($currentLoans->count())
                        <ul class="space-y-3">
                            @foreach($currentLoans as $loan)
                                <li class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $loan->book->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $loan->book->authors->pluck('name')->join(', ') }}</p>
                                        <p class="text-xs text-gray-500">Due: {{ optional($loan->expected_end_at)?->toFormattedDateString() }} · {{ $loan->due_status }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600">No active loans.</p>
                    @endif
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <h3 class="text-lg font-semibold mb-3">Borrowing History</h3>
                    @if($borrowingHistory->count())
                        <ul class="space-y-3">
                            @foreach($borrowingHistory as $history)
                                <li>
                                    <p class="font-semibold text-gray-900">{{ $history->book->name }}</p>
                                    <p class="text-sm text-gray-600">Returned on {{ optional($history->updated_at)?->toFormattedDateString() }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-600">No past loans yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- All Requests Section for Admins -->
        @if(auth()->user()->is_admin)
            <div class="mb-4 mt-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 gap-3">
                    <x-sections.header>All Book Requests</x-sections.header>
                    <div class="flex gap-2 flex-wrap">
                            <x-buttons.link :href="route('requisitions.export.csv')" class="bg-green-600 hover:bg-green-700">Export Borrowings CSV</x-buttons.link>
                    </div>
                </div>
                @if($allRequisitions && $allRequisitions->count())
                    <x-tables.requisitions-admin :requisitions="$allRequisitions" />
                    {{ $allRequisitions->links() }}
                @else
                    <p class="text-gray-600">No requests found.</p>
                @endif
            </div>
        @endif
    </div>
</x-layout>
