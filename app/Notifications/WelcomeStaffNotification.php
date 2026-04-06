<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class WelcomeStaffNotification extends Notification
{
    protected $user;
    protected $adminName;

    public function __construct($user, $adminName)
    {
        $this->user = $user;
        $this->adminName = $adminName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'welcome',
            'admin_name' => $this->adminName,
            'message' => "Welcome to the system, {$this->user->name}! Your account was created by Admin {$this->adminName}.",
        ];
    }
}
