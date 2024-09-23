<x-app-layout>
    <x-slot name="header">
        <div class="bg-blue-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">
                {{ __('Notifications') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <ul>
                @forelse ($notifications as $notification)
                    <li class="mb-4">
                        <p>{{ $notification->data['message'] }}</p> <!-- Modify as needed -->
                        <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                    </li>
                @empty
                    <li>You have no notifications.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>
