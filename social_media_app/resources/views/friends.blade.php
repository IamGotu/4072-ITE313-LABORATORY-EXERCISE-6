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
            <div class="bg-green-500 text-white p-2 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 text-white p-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-6">
            <h2 class="text-xl">Suggested Friends</h2>
            <ul>
                @foreach($suggestedFriends as $suggested)
                    <li class="flex justify-between items-center p-2 border-b">
                        <span>{{ $suggested->name }}</span>

                        @php
                            $friendship = Auth::user()->friends()->where('friend_id', $suggested->id)->first();
                        @endphp

                        @if($friendship && $friendship->pivot->status === 'pending')
                            <form method="POST" action="{{ route('friends.cancel', $suggested->id) }}">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg mt-2">
                                    {{ __('Cancel Friend Request') }}
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('friends.add', $suggested->id) }}">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-2">
                                    {{ __('Add Friend') }}
                                </button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>