<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLikedNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $liker; // Store the user who liked the post

    public function __construct($post, $liker)
    {
        $this->post = $post;
        $this->liker = $liker; // Set the user who liked the post
    }

    public function via($notifiable)
    {
        return ['database']; // or 'mail', 'broadcast', etc.
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->liker->name} liked your post: '{$this->post->content}'",
            'post_id' => $this->post->id,
        ];
    }
}