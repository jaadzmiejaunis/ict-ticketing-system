<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    use Queueable;

    protected $ticket;
    protected $comment;
    protected $type;

    public function __construct($ticket, $comment, $type = 'reply')
    {
        $this->ticket = $ticket;
        $this->comment = $comment;
        $this->type = $type;
    }

    // THIS METHOD FIXES THE ERROR IN YOUR SCREENSHOT
    public function via($notifiable)
    {
        return ['database']; // Mandatory method
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'comment_user' => $this->comment->user->name,
            'type' => $this->type, // 'mention' or 'reply'
        ];
    }
}
