<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard'); // Redirect to dashboard if logged in
    } else {
        return redirect()->route('login'); // Redirect to login if not logged in
    }
});

// Dashboard route with authentication
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.profile');
    Route::patch('/profile/update-email', [ProfileController::class, 'updateEmail'])->name('profile.updateEmail');
    Route::patch('/profile/update-name', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Post routes
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);
    Route::post('/posts/{post}/like', [PostController::class, 'likePost']);
    Route::post('/posts/{post}/comment', [PostController::class, 'addComment']);
    Route::put('/comments/{id}', [PostController::class, 'updateComment']);
    Route::delete('/comments/{id}', [PostController::class, 'deleteComment']);

    // Notification routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::post('/notifications/mark-as-read/{notificationId}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/delete/{notificationId}', [NotificationController::class, 'destroy'])->name('notifications.delete');
    Route::delete('/notifications/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.deleteAllRead');

    // Friends routes
    Route::get('/friends', [FriendController::class, 'index'])->name('friends');
    Route::post('/friends/add/{friendId}', [FriendController::class, 'addFriend'])->name('friends.add');
    Route::delete('/friends/{friendId}/unfriend', [FriendController::class, 'unfriend'])->name('friends.unfriend');
    Route::post('/friends/cancel/{friendId}', [FriendController::class, 'cancelFriendRequest'])->name('friends.cancel');
    Route::post('/friends/accept/{friendId}', [FriendController::class, 'acceptFriend'])->name('friends.accept');
    Route::delete('/friends/decline/{friendId}', [FriendController::class, 'declineFriendRequest'])->name('friends.decline');
});

Route::middleware(['auth'])->group(function () {
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/conversation/{userId}', [MessageController::class, 'getConversation'])->name('messages.conversation');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::post('messages/send', [MessageController::class, 'send'])->name('messages.send');
    Route::delete('/messages/{id}/delete', [MessageController::class, 'deleteMessage']);
    Route::post('/messages/conversation/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.markAsRead');
    Route::get('messages/retrieve/{userId}', [MessageController::class, 'retrieve'])->name('messages.retrieve');
    Route::get('search', [MessageController::class, 'search'])->name('messages.search');
});

// Optional: API routes if needed
// You can move this part if you're handling API separately
// Route::prefix('api')->middleware('auth:api')->group(function () {
//     Route::post('/posts', [PostController::class, 'store']);
//     Route::get('/posts', [PostController::class, 'index']);
// });

require __DIR__.'/auth.php';
