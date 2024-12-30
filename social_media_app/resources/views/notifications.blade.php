<x-app-layout>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        <div class="bg-white p-6 rounded-lg shadow-md">

            @if(session('message'))
                <div class="bg-green-500 text-white p-2 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500 text-white p-2 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Flexbox container for buttons -->
            <div class="flex space-x-4 mb-4"> <!-- Added flex and space-x-4 for spacing between buttons -->
                <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-4 rounded-lg">
                        Mark All as Read
                    </button>
                </form>

                <form method="POST" action="{{ route('notifications.deleteAllRead') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-4 rounded-lg">
                        Delete All Read Notifications
                    </button>
                </form>
            </div>

            <ul>
                @forelse ($notifications as $notification)
                    <li class="mb-4">
                        <p>{{ $notification->data['message'] }}</p>
                        <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                        <div class="mt-2">
                            @if(is_null($notification->read_at))
                                <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded">
                                        Mark as Read
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('notifications.delete', $notification->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </li>
                @empty
                    <li>You have no notifications.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>