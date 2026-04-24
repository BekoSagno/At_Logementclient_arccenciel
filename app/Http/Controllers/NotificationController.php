<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Récupérer les notifications non lues (API)
     */
    public function unread()
    {
        $notifications = Auth::user()
            ->unreadNotifications()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $count = Auth::user()->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'count' => $count,
        ]);
    }

    /**
     * Récupérer toutes les notifications
     */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user.notifications', compact('notifications'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        Auth::user()
            ->unreadNotifications()
            ->update([
                'read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }
}
