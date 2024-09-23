<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // Fetch user notifications
        $notifications = Auth::user()->notifications;

        // Make sure you reference 'notifications' (without .blade.php extension)
        return view('notifications', compact('notifications'));
    }
}
