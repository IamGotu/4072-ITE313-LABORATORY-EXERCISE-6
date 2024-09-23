<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentAddedNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $comment;
    protected $commenter; // Store the user who commented

    public function __construct($post, $comment, $commenter)
    {
        $this->post = $post;
        $this->comment = $comment;
        $this->commenter = $commenter; // Set the user who commented
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "{$this->commenter->name} commented on your post: '{$this->comment->comment}'",
            'post_id' => $this->post->id,
        ];
    }
}