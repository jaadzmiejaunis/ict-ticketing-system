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
            'title' => $this->getNotificationTitle(),
            'message' => $this->getMessage(),
        ];
    }

    protected function getNotificationTitle()
    {
        return match($this->type) {
            'status_change' => 'Status Updated',
            'assigned'      => 'Task Claimed',
            'resolved'      => 'Task Resolved',
            'transferred'   => 'New Assignment',
            default         => 'Ticket Update',
        };
    }

    protected function getMessage()
    {
        return match($this->type) {
            'status_change' => "{$this->actorName} updated the status of Ticket #{$this->ticket->id}",
            'assigned'      => "{$this->actorName} claimed and assigned Ticket #{$this->ticket->id} to themselves",
            'resolved'      => "{$this->actorName} has marked Ticket #{$this->ticket->id} as Resolved",
            // Updated text for admin assignments
            'transferred'   => "An admin has assigned Ticket #{$this->ticket->id} to you.",
            default         => "{$this->actorName} made an update to Ticket #{$this->ticket->id}",
        };
    }
}
