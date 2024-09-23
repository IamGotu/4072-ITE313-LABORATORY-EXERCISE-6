<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Comment;
use App\Notifications\PostLikedNotification;
use App\Notifications\CommentAddedNotification;

class PostController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'content' => 'required|max:255',
        ]);

        $post = new Post();
        $post->content = $request->content;
        $post->user_id = Auth::id();
        $post->save();

        return response()->json($post, 201);
    }

    public function index() {
        $posts = Post::with('user', 'comments.user')->latest()->get();

        foreach ($posts as $post) {
            $post->userHasLiked = $post->likes()->where('user_id', Auth::id())->exists();
        }

        return response()->json($posts);
    }

    public function destroy($id) {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function likePost(Post $post)
    {
        $existingLike = $post->likes()->where('user_id', Auth::id())->first();
    
        if ($existingLike) {
            $existingLike->delete();
            $post->decrement('likes_count');
            return response()->json(['message' => 'Post unliked']);
        } else {
            $post->likes()->create(['user_id' => Auth::id()]);
            $post->increment('likes_count');
    
            // Notify the post owner
            $liker = Auth::user(); // Get the current user who liked the post
            $post->user->notify(new PostLikedNotification($post, $liker));
    
            return response()->json(['message' => 'Post liked']);
        }
    }
    
    public function addComment(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);
    
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->user_id = Auth::id();
        $comment->post_id = $post->id;
        $comment->save();
    
        // Notify the post owner
        $commenter = Auth::user(); // Get the current user who commented
        $post->user->notify(new CommentAddedNotification($post, $comment, $commenter));
    
        return response()->json($comment, 201);
    }
}
