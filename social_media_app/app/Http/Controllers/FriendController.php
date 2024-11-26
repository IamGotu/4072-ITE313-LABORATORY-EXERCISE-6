<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FriendRequestAcceptedNotification;
use App\Notifications\FriendRequestNotification;

class FriendController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Suggested friends (users not already friends or having pending requests)
        $suggestedFriends = User::where('id', '!=', $user->id)
            ->whereDoesntHave('friends', function ($query) use ($user) {
                $query->where('friend_id', $user->id)
                      ->orWhere('user_id', $user->id);
            })
            ->get();
        
        // Incoming friend requests (where the logged-in user is the recipient)
        $incomingRequests = $user->friendRequestsReceived;
        
        // Outgoing friend requests (where the logged-in user is the sender)
        $outgoingRequests = $user->friendRequestsSent;
        
        // Friends (confirmed friendships)
        $friends = $user->friends()->wherePivot('status', 'confirmed')->get(); // Get confirmed friends
        
        // Return the view with all variables
        return view('friends', compact('suggestedFriends', 'incomingRequests', 'outgoingRequests', 'friends'));
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

    public function unfriend($friendId)
    {
        $user = Auth::user();
        
        // Check if the user is friends with the given friendId
        $friendship = $user->friends()->where('friend_id', $friendId)->wherePivot('status', 'confirmed')->first();

        if ($friendship) {
            // Remove the friend from the relationship
            $user->friends()->detach($friendId);
            return redirect()->back()->with('message', 'You have unfriended this user.');
        }

        return redirect()->back()->with('error', 'Friend not found.');
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

    public function acceptFriend($friendId)
    {
        $user = Auth::user();
        
        // Check if the logged-in user has received a pending friend request from the other user
        $friendship = $user->friendRequestsReceived()->where('user_id', $friendId)->wherePivot('status', 'pending')->first();
    
        if ($friendship) {
            // Accept the friend request by updating the pivot table's 'status' to 'confirmed'
            $friendship->pivot->update(['status' => 'confirmed']);
            
            // Also check the outgoing request on the other user's side (to update their view of the relationship)
            $sentFriendship = $user->friendRequestsSent()->where('friend_id', $friendId)->wherePivot('status', 'pending')->first();
            
            // If the outgoing request is found, update the status to 'confirmed'
            if ($sentFriendship) {
                $sentFriendship->pivot->update(['status' => 'confirmed']);
            }
            
            // Notify the sender that their request was accepted
            $sender = User::find($friendId);
            $sender->notify(new FriendRequestAcceptedNotification($user));
    
            return redirect()->back()->with('message', 'Friend request accepted.');
        }
    
        // If no incoming friend request was found
        return redirect()->back()->with('error', 'Friend request not found.');
    }    
                                
    public function declineFriendRequest($friendId)
    {
        $user = Auth::user();
        
        // Find the incoming friend request where the logged-in user is the recipient (friend_id)
        $friendship = $user->friendRequestsReceived()->where('user_id', $friendId)->wherePivot('status', 'pending')->first();
    
        if (!$friendship) {
            return redirect()->back()->with('error', 'Friend request not found.');
        }
    
        // Remove the friend request (decline it)
        $user->friendRequestsReceived()->detach($friendId);
    
        return redirect()->back()->with('message', 'Friend request declined.');
    }
}