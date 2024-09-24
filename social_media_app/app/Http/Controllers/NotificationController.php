<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->get();
        return view('notifications', compact('notifications'));
    }

    public function markAsRead($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return redirect()->back()->with('message', 'Notification marked as read!');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('message', 'All notifications marked as read!');
    }

    public function destroy($notificationId)
    {
        $notification = Auth::user()->notifications()->findOrFail($notificationId);
        
        // Ensure the notification is marked as read before deletion
        if ($notification->read_at) {
            $notification->delete();
            return redirect()->back()->with('message', 'Notification deleted successfully.');
        }
        
        return redirect()->back()->with('error', 'You must mark the notification as read before deleting it.');
    }

    public function deleteAllRead()
    {
        // Get all read notifications of the authenticated user
        $readNotifications = Auth::user()->readNotifications;

        // Delete all read notifications
        foreach ($readNotifications as $notification) {
            $notification->delete();
        }

        return redirect()->back()->with('message', 'All read notifications deleted successfully.');
    }
}