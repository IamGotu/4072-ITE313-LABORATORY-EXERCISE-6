<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        'name',
        'email',
        'password',
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id')
                    ->withPivot('status') // Include the pivot status
                    ->withTimestamps();
    }

    // Method to get the friend requests received
    public function friendRequestsReceived()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'friend_id', 'user_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'pending')
                    ->withTimestamps();
    }

    // Method to get the friend requests sent
    public function friendRequestsSent()
    {
        return $this->belongsToMany(User::class, 'friend_user', 'user_id', 'friend_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'pending')
                    ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
