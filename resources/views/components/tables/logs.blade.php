@php
    use Illuminate\Support\Str;
@endphp

<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Object ID</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($logs as $log)
            <tr class="even:bg-blue-50 hover:bg-blue-100">
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $log->date }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $log->time }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $log->user->name ?? 'System' }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $log->module }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $log->object_id ?? '—' }}</td>
                <td class="px-4 py-2 text-sm text-gray-900">{{ $log->description }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $log->ip_address }}</td>
                <td class="px-4 py-2 text-sm text-gray-500">{{ Str::limit($log->user_agent, 40) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-4 py-6 text-center text-gray-500">No logs found for the selected filters.</td>
            </tr>
        @endforelse
    </tbody>
</table>
