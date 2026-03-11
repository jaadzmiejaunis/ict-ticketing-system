<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    protected $ticket;
    protected $comment;

    public function __construct($ticket, $comment)
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
    }

    public function via($notifiable) { return ['database']; }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'comment_user' => $this->comment->user->name,
            'message' => 'New activity on ticket #' . $this->ticket->id,
        ];
    }
}
