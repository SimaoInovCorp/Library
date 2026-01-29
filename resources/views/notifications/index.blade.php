<x-layout>
    <x-slot name="header">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Notifications</h1>
    </x-slot>
    <div class="max-w-2xl mx-auto py-8">
        <h2 class="text-xl font-bold mb-4">Your Notifications</h2>
        @if(Auth::user()->notifications->count())
            <ul class="divide-y divide-gray-200">
                @foreach(Auth::user()->notifications as $notification)
                    <li class="py-4 flex items-center justify-between">
                        <div>
                            <div class="text-gray-900">{{ $notification->data['message'] ?? $notification->data['body'] ?? 'Notification' }}</div>
                            <div class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        @if($notification->unread())
                            <form method="POST" action="{{ route('notifications.markAsRead', $notification) }}">
                                @csrf
                                <button class="text-blue-600 hover:underline text-xs">Mark as read</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-gray-500">You have no notifications.</div>
        @endif
    </div>
</x-layout>
