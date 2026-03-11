<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommentNotification;
use Illuminate\Support\Str; // Fixes red Str highlight

class TicketCommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'comment' => 'required|string|max:5000',
            'parent_id' => 'nullable|exists:ticket_comments,id',
        ]);

        // 1. Create the comment with parent_id link
        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(), // Use Auth::id() instead of id()
            'parent_id' => $request->parent_id,
            'comment'   => $request->comment,
        ]);

        // 2. Identify people to notify (excluding yourself)
        $participants = collect([$ticket->user, $ticket->assignee])
            ->filter()->unique('id')->where('id', '!=', Auth::id());

        // 3. Scan for Mentions (@Name)
        $mentionedUsers = collect();
        if (Str::contains($comment->comment, '@')) {
            $mentionedUsers = User::where('id', '!=', Auth::id())->get()->filter(function($user) use ($comment) {
                return Str::contains($comment->comment, '@' . $user->name);
            });
        }

        // 4. Send notifications
        foreach ($mentionedUsers as $mUser) {
            $mUser->notify(new CommentNotification($ticket, $comment, 'mention'));
        }
        foreach ($participants->diff($mentionedUsers) as $oUser) {
            $oUser->notify(new CommentNotification($ticket, $comment, 'reply'));
        }

        $ticket->touch();
        return back()->with('success', 'Comment posted!');
    }
}
