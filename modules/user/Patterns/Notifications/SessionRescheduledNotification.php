<?php

namespace Modules\User\Patterns\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SessionRescheduledNotification extends Notification implements ShouldQueue
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
            ->subject('Session Rescheduled')
            ->line("A session has been Rescheduled on: {$this->session['scheduled_at']}")
            ->line('Please check your dashboard for details.');
    }

    public function toArray($notifiable)
    {
        return $this->session;
    }
}
