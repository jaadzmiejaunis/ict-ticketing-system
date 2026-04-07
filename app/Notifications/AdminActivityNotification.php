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
        $targetName = $this->targetUser['name'] ?? $this->targetUser->name;

        // Map the internal action keywords to professional titles
        $titles = [
            'created' => 'New Account Created',
            'updated' => 'Account Modified',
            'activated' => 'Account Activated',
            'deactivated' => 'Account Deactivated',
            'permanently deleted' => 'Account Purged'
        ];

        return [
            'type' => 'admin_action',
            'title' => $titles[$this->action] ?? 'Admin Activity',
            'action' => $this->action,
            'target_name' => $targetName,
            'admin_name' => $this->adminName,
            'message' => "Admin {$this->adminName} has successfully {$this->action} the account for {$targetName}.",
        ];
    }
}
