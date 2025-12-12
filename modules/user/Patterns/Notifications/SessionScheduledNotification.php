<?php

namespace Modules\User\Patterns\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SessionScheduledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $session;

    public function __construct(array $session)
    {
        $this->session = $session;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Session Scheduled')
            ->line("A session has been scheduled on: {$this->session['scheduled_at']}")
            ->line('Please check your dashboard for details.');
    }

    public function toArray($notifiable)
    {
        return $this->session;
    }
}
