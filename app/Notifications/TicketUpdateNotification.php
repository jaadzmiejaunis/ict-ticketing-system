<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class TicketUpdateNotification extends Notification
{
    protected $ticket;
    protected $type;
    protected $actorName;

    public function __construct($ticket, $type, $actorName)
    {
        $this->ticket = $ticket;
        $this->type = $type;
        $this->actorName = $actorName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_title' => $this->ticket->title,
            'actor_name' => $this->actorName,
            'type' => $this->type,
            'message' => $this->getMessage(),
        ];
    }

    protected function getMessage()
    {
        return match($this->type) {
            'status_change' => "updated the status of Ticket #{$this->ticket->id}",
            'assigned'      => "claimed and assigned Ticket #{$this->ticket->id} to themselves",
            'resolved'      => "has marked Ticket #{$this->ticket->id} as Resolved",
            'transferred'   => "has assigned Ticket #{$this->ticket->id} to you",
            default         => "made an update to Ticket #{$this->ticket->id}",
        };
    }
}
