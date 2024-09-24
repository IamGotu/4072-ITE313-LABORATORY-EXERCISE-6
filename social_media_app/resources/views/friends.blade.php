<x-app-layout>
    <x-slot name="header">
        <div class="bg-blue-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">
                {{ __('Friends') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if(session('message'))
            <div class="bg-green-500 text-white p-2 rounded">
                {{ session('message') }}
            </div>
        @endif

        <div class="mt-6">
            <h2 class="text-xl">Suggested Friends</h2>
            <ul>
                @foreach($suggestedFriends as $suggested)
                    <li class="flex justify-between items-center p-2 border-b">
                        <span>{{ $suggested->name }}</span>
                        <form method="POST" action="{{ route('friends.add', $suggested->id) }}">
                            @csrf
                            <button type="submit" class="bg-blue-500 text-white p-1 rounded">Add Friend</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>