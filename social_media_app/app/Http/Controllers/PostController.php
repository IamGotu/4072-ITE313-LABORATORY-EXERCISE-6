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
        $post->visibility = $request->visibility;
        $post->save();

        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
    
        $validated = $request->validate([
            'content' => 'required|string',
            'visibility' => 'required|in:Public,Friends,Only me',
        ]);
    
        $post->content = $validated['content'];
        $post->visibility = $validated['visibility'];
        $post->save();
    
        return response()->json($post);
    }
    
    public function index()
    {
        $authUserId = Auth::id();
    
        // Fetch posts with user and comments relationships
        $posts = Post::with('user', 'comments.user')->latest()->get();
    
        // Filter posts based on visibility and user
        $filteredPosts = $posts->filter(function($post) use ($authUserId) {
            if ($post->user_id == $authUserId) {
                // The post belongs to the authenticated user, so they can always see it
                return true;
            }
    
            switch ($post->visibility) {
                case 'Public':
                    return true; // Everyone can see public posts
                case 'Friends':
                    // Check if the authenticated user is a confirmed friend of the post owner
                    return $post->user->friends()->where('friend_id', $authUserId)->where('status', 'confirmed')->exists();
                case 'Only me':
                    return false; // Only the post owner can see "only me" posts
                default:
                    return false; // Default to hidden if visibility is not set properly
            }
        });
    
        // Add the userHasLiked property for the posts the user has liked
        foreach ($filteredPosts as $post) {
            $post->userHasLiked = $post->likes()->where('user_id', $authUserId)->exists();
        }
    
        // Return the filtered posts
        return response()->json($filteredPosts);
    }
    
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
    
        // Check if the logged-in user is the owner of the post
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        // Delete the post
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
