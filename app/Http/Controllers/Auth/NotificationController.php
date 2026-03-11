<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $allNotifications = Ticket::withTrashed()
            ->where(function($q) {
                $q->where('user_id', Auth::id())
                  ->orWhere('assigned_to', Auth::id())
                  ->orWhere('assigned_by', Auth::id()); // Tracks tasks assigned by you
            })
            ->latest('updated_at')
            ->paginate(20);

        return view('notifications.index', compact('allNotifications'));
    }

    public function readAll()
    {
        /** @var \App\Models\User $user */ // This tells the IDE to look at your User model
        $user = Auth::user();

        if ($user) {
            $user->update([
                'last_read_notifications_at' => now()
            ]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }
}
