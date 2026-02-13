<x-layout>
    <x-slot name="header">
        My Requests
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
            <h2 class="text-2xl font-semibold tracking-tight text-gray-800 border-l-4 border-blue-200 pl-2 mb-4 font-bold mb-4">Available Books</h2>
            @if($availableBooks->count())
                <x-tables.available-books :books="$availableBooks" />
                {{ $availableBooks->links() }}
            @else
                <p class="text-gray-600">No available books at the moment.</p>
            @endif
        </div>

        <!-- My book Requests Section -->
        <div>
            <h2 class="text-2xl font-semibold tracking-tight text-gray-800 border-l-4 border-blue-200 pl-2 mb-4 font-bold mb-4">My Book Requests</h2>
            @if($requisitions->count())
                <x-tables.requisitions :requisitions="$requisitions" />
                {{ $requisitions->links() }}
            @else
                <p class="text-gray-600">You have not made any requests yet.</p>
            @endif
        </div>

        <!-- All Requests Section for Admins -->
        @if(auth()->user()->is_admin)
            <div class="mb-4 mt-8">
                <h2 class="text-2xl font-semibold tracking-tight text-gray-800 border-l-4 border-blue-200 pl-2 mb-4 font-bold mb-4">All Book Requests</h2>
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
