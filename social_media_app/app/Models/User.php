<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'email',
        'password',
        'gender',
        'pronouns',
        'birth_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',  // Correctly cast birth_date as a date
    ];

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id')
                    ->withPivot('status') // Access the pivot column (status)
                    ->withTimestamps();
    }

    // Method to get the friend requests received (incoming)
    public function friendRequestsSent()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'pending');
    }    
    
    public function friendRequestsReceived()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'friend_id', 'user_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'pending');
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
        
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
