<?php

namespace App\Notifications;

use App\Services\BrevoService; // Point to Brevo
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Call the Brevo Service
        BrevoService::sendEmail(
            $notifiable->email,
            'Reset Your ICT Portal Password',
            "<div style='font-family: sans-serif; padding: 20px;'>
                <h2>ICT Support Portal</h2>
                <p>You requested a password reset. Click the button below to continue:</p>
                <a href='{$url}' style='background:#6366f1; color:white; padding:12px 24px; border-radius:8px; text-decoration:none; display:inline-block;'>Reset Password</a>
                <p>If you didn't ask for this, you can ignore this email.</p>
            </div>"
        );

        return (new MailMessage)->view('emails.empty');
    }
}
