<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    protected $ticket;
    protected $comment;
    protected $type; // 'mention', 'reply', or 'update'

    public function __construct($ticket, $comment, $type = 'update')
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
        $this->type = $type;
    }

    // MANDATORY: Tells Laravel to save this in the 'notifications' table
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'comment_user' => $this->comment->user->name,
            'type' => $this->type,
            'message' => $this->getMessage(),
        ];
    }

    protected function getMessage()
    {
        return match($this->type) {
            'mention' => "pinged you in a comment",
            'reply'   => "replied to your comment",
            default   => "posted a new update",
        };
    }
}
