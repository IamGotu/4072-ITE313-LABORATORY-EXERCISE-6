<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use App\Events\NewMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
    
        // Fetch all users except the currently authenticated user
        $users = User::where('id', '!=', $userId)->get();
    
        // Get conversations for the logged-in user
        $conversations = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver']) // Load sender and receiver relationships
            ->get()
            ->groupBy('conversation_id');
    
        $formattedConversations = [];
    
        foreach ($conversations as $conversationId => $messages) {
            $latestMessage = $messages->last(); // Get the last message in this conversation
            $otherUser = $latestMessage->sender_id == $userId ? $latestMessage->receiver : $latestMessage->sender;
    
            $formattedConversations[] = [
                'otherUserId' => $otherUser->id,
                'otherUserName' => $otherUser->name,
                'latestMessage' => $latestMessage,
            ];
        }
    
        // Pass the $users and $formattedConversations to the view
        return view('messages.index', compact('users', 'formattedConversations'));
    }
                            
    public function send(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string|max:255',
        ]);

        // Create a new message
        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $validatedData['receiver_id'],
            'content' => $validatedData['content'],
        ]);

        // Broadcast the new message
        broadcast(new NewMessage($message))->toOthers(); // This line sends the message to others in the channel

        // Return a response
        return redirect()->route('messages')->with('message', 'Message sent successfully');
    }

    public function markAsRead($id)
    {
        // Assuming $id is the conversation ID or the user ID of the other participant
        $conversation = Conversation::where('user_id', Auth::id())
                                    ->where('other_user_id', $id)
                                    ->first();

        if ($conversation) {
            // Mark the conversation as read (implement your logic here)
            $conversation->read_by_users()->attach(Auth::id()); // Example of a many-to-many relationship
        }

        return response()->json(['success' => true]);
    }

    public function retrieve($userId)
    {
        // Retrieve messages between the authenticated user and another user
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', Auth::id());
        })->get();

        return response()->json($messages);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $users = User::where('name', 'like', "%{$query}%")->get();

        return response()->json($users);
    }

    public function getConversation($userId)
    {
        $currentUserId = Auth::id();

        // Fetch messages between the current user and the specified user
        $messages = Message::where(function ($query) use ($currentUserId, $userId) {
            $query->where('sender_id', $currentUserId)
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($currentUserId, $userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $currentUserId);
        })->orderBy('created_at', 'asc') // Order messages by date
        ->with('sender', 'receiver') // Load sender and receiver relationships
        ->get();

        return response()->json($messages); // Return the messages as JSON
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        // Check if the authenticated user is the sender or receiver
        if ($message->sender_id === Auth::id() || $message->receiver_id === Auth::id()) {
            $message->delete();
            return redirect()->back()->with('message', 'Message deleted successfully.');
        }

        return redirect()->back()->with('error', 'You are not authorized to delete this message.');
    }

    public function startConversation($userOneId, $userTwoId)
    {
        // Check if the conversation already exists
        $conversation = Conversation::where(function ($query) use ($userOneId, $userTwoId) {
            $query->where('user_one_id', $userOneId)
                ->where('user_two_id', $userTwoId);
        })->orWhere(function ($query) use ($userOneId, $userTwoId) {
            $query->where('user_one_id', $userTwoId)
                ->where('user_two_id', $userOneId);
        })->first();

        if (!$conversation) {
            // Create a new conversation
            $conversation = Conversation::create([
                'user_one_id' => $userOneId,
                'user_two_id' => $userTwoId,
            ]);
        }

        return $conversation;
    }

    public function deleteConversation($conversationId)
    {
        $conversation = Conversation::find($conversationId);
        $userId = Auth::id();

        // Check if the user is user_one or user_two
        if ($conversation->user_one_id == $userId) {
            $conversation->deleted_by_user_one = true;
        } elseif ($conversation->user_two_id == $userId) {
            $conversation->deleted_by_user_two = true;
        }

        $conversation->save();

        // If both users have deleted the conversation, delete it from the database
        if ($conversation->deleted_by_user_one && $conversation->deleted_by_user_two) {
            $conversation->delete();
        }

        return response()->json(['message' => 'Conversation deleted for this user.']);
    }

    public function deleteMessage($messageId)
    {
        $userId = Auth::id();

        // Find the message by its ID
        $message = Message::findOrFail($messageId);

        // Check if the current user is the sender or the receiver
        if ($message->sender_id === $userId) {
            // Mark the message as deleted by the sender
            $message->deleted_by_sender = true;
        } elseif ($message->receiver_id === $userId) {
            // Mark the message as deleted by the receiver
            $message->deleted_by_receiver = true;
        }

        // Save the changes
        $message->save();

        // If both sender and receiver have deleted the message, delete it from the database
        if ($message->deleted_by_sender && $message->deleted_by_receiver) {
            $message->delete();
        }

        return response()->json(['success' => true]);
    }


}