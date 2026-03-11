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
        $request->validate(['comment' => 'required', 'parent_id' => 'nullable|exists:ticket_comments,id']);

        $comment = TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => Auth::id(),
            'parent_id' => $request->parent_id,
            'comment'   => $request->comment,
        ]);

        // 1. Find Mentioned Users (@Name)
        $mentionedUsers = collect();
        if (Str::contains($comment->comment, '@')) {
            $mentionedUsers = User::where('id', '!=', Auth::id())->get()->filter(function($u) use ($comment) {
                return Str::contains($comment->comment, '@' . $u->name);
            });
        }

        // 2. Find the Parent Comment Author (if this is a reply)
        $replyTarget = null;
        if ($comment->parent_id) {
            $parent = TicketComment::find($comment->parent_id);
            if ($parent && $parent->user_id !== Auth::id()) {
                $replyTarget = $parent->user;
            }
        }

        // 3. Find Ticket Stakeholders (Reporter & Assignee)
        $stakeholders = collect([$ticket->user, $ticket->assignee])
            ->filter()
            ->unique('id')
            ->where('id', '!=', Auth::id());

        // --- SEND NOTIFICATIONS ---

        // Send Mentions (Priority 1)
        foreach ($mentionedUsers as $user) {
            $user->notify(new CommentNotification($ticket, $comment, 'mention'));
        }

        // Send Reply Alert (Priority 2 - Only if they weren't already @pinged)
        if ($replyTarget && !$mentionedUsers->contains('id', $replyTarget->id)) {
            $replyTarget->notify(new CommentNotification($ticket, $comment, 'reply'));
        }

        // Send General Activity Alert to others
        $others = $stakeholders->diff($mentionedUsers);
        if ($replyTarget) { $others = $others->where('id', '!=', $replyTarget->id); }

        foreach ($others as $user) {
            $user->notify(new CommentNotification($ticket, $comment, 'update'));
        }

        $ticket->touch();
        return back();
    }
}
