<x-layout>
    <x-slot name="heading">My Requests</x-slot>
    <div class="container mx-auto py-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Available Books Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Available Books</h2>
            @if($availableBooks->count())
                <table class="table-auto w-full mb-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">ISBN</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Publisher</th>
                            <th class="px-4 py-2 text-left">Authors</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($availableBooks as $book)
                            <tr>
                                <td class="px-4 py-2">{{ $book->isbn }}</td>
                                <td class="px-4 py-2">{{ $book->name }}</td>
                                <td class="px-4 py-2">{{ $book->publisher->name ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @foreach($book->authors as $author)
                                        <span class="badge">{{ $author->name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-2">
                                    <form action="{{ route('books.requisitions.store', $book) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                            Request Loan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $availableBooks->links() }}
            @else
                <p class="text-gray-600">No available books at the moment.</p>
            @endif
        </div>

        <!-- My Requests Section -->
        <div>
            <h2 class="text-2xl font-bold mb-4">My Book Requests</h2>
            @if($requisitions->count())
                <table class="table-auto w-full mb-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Book</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Requested At</th>
                            <th class="px-4 py-2 text-left">Expected End</th>
                            <th class="px-4 py-2 text-left">Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requisitions as $req)
                            <tr>
                                <td class="px-4 py-2">{{ $req->book->name ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-sm
                                        {{ $req->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                        {{ $req->status === 'approved' ? 'bg-green-200 text-green-800' : '' }}
                                        {{ $req->status === 'rejected' ? 'bg-red-200 text-red-800' : '' }}">
                                        {{ ucfirst($req->status) }}
                                    </span>
                                    @if($req->status === 'approved')
                                        <form action="{{ route('requisitions.return', $req) }}" method="POST" class="inline ml-2">
                                            @csrf
                                            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm">Return</button>
                                        </form>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $req->requested_at ? \Carbon\Carbon::parse($req->requested_at)->format('Y-m-d H:i') : '-' }}</td>
                                <td class="px-4 py-2">{{ $req->expected_end_at ? \Carbon\Carbon::parse($req->expected_end_at)->format('Y-m-d H:i') : '-' }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $status = $req->due_status;
                                    @endphp
                                    <span class="px-2 py-1 rounded text-sm
                                        {{ $status === 'Overdue' ? 'bg-red-200 text-red-800' : '' }}
                                        {{ $status === 'Due Today' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                        {{ $status === 'On Time' ? 'bg-green-200 text-green-800' : '' }}
                                    ">
                                        {{ $status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-600">You have not made any requests yet.</p>
            @endif
        </div>

        <!-- All Requests Section for Admins -->
        @if(auth()->user()->is_admin)
            <div class="mb-8">
                <h2 class="text-2xl font-bold mb-4">All Book Requests</h2>
                @if($allRequisitions && $allRequisitions->count())
                    <table class="table-auto w-full mb-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">User</th>
                                <th class="px-4 py-2 text-left">Book</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Requested At</th>
                                <th class="px-4 py-2 text-left">Expected End</th>
                                <th class="px-4 py-2 text-left">Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allRequisitions as $req)
                                <tr>
                                    <td class="px-4 py-2">{{ $req->user->name ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $req->book->name ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 rounded text-sm
                                            {{ $req->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                            {{ $req->status === 'approved' ? 'bg-green-200 text-green-800' : '' }}
                                            {{ $req->status === 'rejected' ? 'bg-red-200 text-red-800' : '' }}">
                                            {{ ucfirst($req->status) }}
                                        </span>
                                        @if($req->status === 'pending')
                                            <form action="{{ route('requisitions.approve', $req) }}" method="POST" class="inline ml-2">
                                                @csrf
                                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">Approve</button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ $req->requested_at ? \Carbon\Carbon::parse($req->requested_at)->format('Y-m-d H:i') : '-' }}</td>
                                    <td class="px-4 py-2">{{ $req->expected_end_at ? \Carbon\Carbon::parse($req->expected_end_at)->format('Y-m-d H:i') : '-' }}</td>
                                    <td class="px-4 py-2">
                                        @php
                                            $status = $req->due_status;
                                        @endphp
                                        <span class="px-2 py-1 rounded text-sm
                                            {{ $status === 'Overdue' ? 'bg-red-200 text-red-800' : '' }}
                                            {{ $status === 'Due Today' ? 'bg-yellow-200 text-yellow-800' : '' }}
                                            {{ $status === 'On Time' ? 'bg-green-200 text-green-800' : '' }}
                                        ">
                                            {{ $status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-600">No requests found.</p>
                @endif
            </div>
        @endif
    </div>
</x-layout>
