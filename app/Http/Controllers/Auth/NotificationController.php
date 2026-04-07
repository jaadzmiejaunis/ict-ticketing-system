<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Strictly force the newest notifications to appear at the very top
        $allNotifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('allNotifications'));
    }

    public function readAll()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            // Mark all database notifications as read
            $user->unreadNotifications->markAsRead();

            $user->update([
                'last_read_notifications_at' => now()
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }
}
