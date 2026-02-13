@props(['requisitions'])

<table class="table-auto w-full border-gray-200 border shadow">
    <thead>
        <tr>
            <th class="px-4 py-2 text-left bg-blue-100">#</th>
            <th class="px-4 py-2 text-left bg-blue-100">Book</th>
            <th class="px-4 py-2 text-left bg-blue-100">Status</th>
            <th class="px-4 py-2 text-left bg-blue-100">Requested At</th>
            <th class="px-4 py-2 text-left bg-blue-100">Expected End</th>
            <th class="px-4 py-2 text-left bg-blue-100">Due</th>
            <th class="px-4 py-2 text-left bg-blue-100">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requisitions as $req)
            <tr class="even:bg-blue-50 hover:bg-blue-100">
                <td class="px-4 py-2 align-middle">{{ $req->number ?? '-' }}</td>
                <td class="px-4 py-2 align-middle">{{ $req->book->name ?? '-' }}</td>
                <td class="px-4 py-2 align-middle">
                    <span class="px-2 py-1 rounded text-sm
                        {{ $req->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : '' }}
                        {{ $req->status === 'approved' ? 'bg-green-200 text-green-800' : '' }}
                        {{ $req->status === 'rejected' ? 'bg-red-200 text-red-800' : '' }}">
                        {{ ucfirst($req->status) }}
                    </span>
                </td>
                <td class="px-4 py-2 align-middle">{{ $req->requested_at ? \Carbon\Carbon::parse($req->requested_at)->format('Y-m-d H:i') : '-' }}</td>
                <td class="px-4 py-2 align-middle">{{ $req->expected_end_at ? \Carbon\Carbon::parse($req->expected_end_at)->format('Y-m-d H:i') : '-' }}</td>
                <td class="px-4 py-2 align-middle">
                    @php $status = $req->due_status; @endphp
                    <span class="px-2 py-1 rounded text-sm
                        {{ $status === 'Overdue' ? 'bg-red-200 text-red-800' : '' }}
                        {{ $status === 'Due Today' ? 'bg-yellow-200 text-yellow-800' : '' }}
                        {{ $status === 'On Time' ? 'bg-green-200 text-green-800' : '' }}">
                        {{ $status }}
                    </span>
                </td>
                <td class="px-4 py-2 align-middle">
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('requisitions.show', $req) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded text-sm font-medium">Details / Review</a>
                        @if($req->status === 'approved')
                            <form action="{{ route('requisitions.return', $req) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-sm font-medium">Return</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
