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

            <!-- Incoming Friend Requests -->
            @if($incomingRequests->isNotEmpty())
                <h2 class="text-xl font-semibold mb-4">Incoming Friend Requests</h2>
                <ul>
                    @foreach($incomingRequests as $request)
                        <li class="flex justify-between items-center p-4 border-b">
                            <!-- User Details -->
                            <span class="flex-grow">{{ $request->first_name }} {{ $request->middle_name }} {{ $request->last_name }} {{ $request->suffix }}</span>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('friends.accept', $request->id) }}">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Accept
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('friends.decline', $request->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Decline
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No incoming friend requests.</p>
            @endif

            <!-- Suggested Friends -->
            <div class="mt-6">
                <h2 class="text-xl">Suggested Friends</h2>
                <ul>
                    @foreach($suggestedFriends as $suggested)
                        <li class="flex justify-between items-center p-2 border-b">
                            <span>{{ $suggested->first_name }} {{ $suggested->middle_name }} {{ $suggested->last_name }} {{ $suggested->suffix }}</span>

                            <form method="POST" action="{{ route('friends.add', $suggested->id) }}">
                                @csrf
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-2">
                                    {{ __('Add Friend') }}
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>