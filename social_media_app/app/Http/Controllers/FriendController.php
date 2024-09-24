<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Get suggested friends for the logged-in user
        $suggestedFriends = User::where('id', '!=', $user->id)
            ->whereDoesntHave('friends', function ($query) use ($user) {
                $query->where('friend_id', $user->id);
            })
            ->get();
    
        return view('friends', compact('suggestedFriends'));
    }
}