<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FriendRequestNotification;

class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get suggested friends for the logged-in user
        $suggestedFriends = User::where('id', '!=', $user->id)
            ->whereDoesntHave('friends', function ($query) use ($user) {
                $query->where('friend_id', $user->id);
            })
            ->get();
    
        return view('friends', compact('suggestedFriends'));
    }

    public function addFriend(Request $request, $friendId)
    {
        $user = Auth::user();
    
        // Check if the friend request already exists
        if ($user->friends()->where('friend_id', $friendId)->exists()) {
            return redirect()->back()->with('message', 'You are already friends with this user.');
        }
    
        // Create a new friendship
        $user->friends()->attach($friendId, ['status' => 'pending']); // Set status to 'pending'
    
        // Send a notification to the user who receives the friend request
        $friend = User::findOrFail($friendId);
        $friend->notify(new FriendRequestNotification($user));
    
        return redirect()->back()->with('message', 'Friend request sent!');
    }
    
    public function cancelFriendRequest($friendId)
    {
        $user = Auth::user();
        
        // Check if the friend request exists and the status is 'pending'
        $friendship = $user->friends()->where('friend_id', $friendId)->first();

        if ($friendship && $friendship->pivot->status === 'pending') {
            // Delete the pending friend request
            $user->friends()->detach($friendId);
            return redirect()->back()->with('message', 'Friend request cancelled.');
        }

        return redirect()->back()->with('error', 'No pending friend request found.');
    }
}