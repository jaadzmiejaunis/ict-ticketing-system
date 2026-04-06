<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AdminActivityNotification extends Notification
{
    protected $action;
    protected $targetUser;
    protected $adminName;

    public function __construct($action, $targetUser, $adminName)
    {
        $this->action = $action;
        $this->targetUser = $targetUser;
        $this->adminName = $adminName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'admin_action',
            'action' => $this->action,
            'target_name' => $this->targetUser['name'] ?? $this->targetUser->name,
            'admin_name' => $this->adminName,
            'message' => "Admin {$this->adminName} has {$this->action} the account: " . ($this->targetUser['name'] ?? $this->targetUser->name),
        ];
    }
}
