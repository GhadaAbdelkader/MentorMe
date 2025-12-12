<?php

namespace Modules\User\Patterns\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
class SessionCancelledNotification extends Notification implements ShouldQueue
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
            ->subject('Session cancelled')
            ->line("A session has been cancelled on: {$this->session['cancelled_at']}")
            ->line('Please check your dashboard for details.');
    }

    public function toArray($notifiable)
    {
        return $this->session;
    }
}
