<?php

namespace Modules\User\Patterns\Actions;

use Modules\User\Patterns\Base\AbstractActionCommand;
use Modules\User\Models\User;
use Modules\User\Patterns\Notifications\SessionCancelledNotification;
use Modules\User\Patterns\Notifications\SessionScheduledNotification;
use Illuminate\Support\Facades\Notification;

class ScheduleSessionCommand extends AbstractActionCommand
{
    public function execute(): array
    {
        $result = $this->service->createSession($this->data);

        if ($result['success']) {
            $after = $result['after'];
            $this->setBackup([
                'id' => $result['session_id'],
                'after' => [
                    'scheduled_at' => $after['scheduled_at'] ?? null,
                    'mentor_id' => $after['mentor_id'] ?? null,
                    'mentee_id' => $after['mentee_id'] ?? null,
                    'duration' => $after['duration'] ?? null,
                    'status' => $after['status'] ?? 'scheduled'
                ],
                'type' => 'create'
            ]);

            // Send notification to mentee
            $mentee = User::find($after['mentee_id']);
            if ($mentee) {
                Notification::send($mentee, new SessionScheduledNotification($after));
            }
        }

        return $result;
    }

    public function undo(): array
    {
        $result = $this->service->deleteSession($this->backup['id']);

        // Notify mentee about undo / cancellation
        $menteeId = $this->backup['after']['mentee_id'] ?? null;
        if ($menteeId) {
            $mentee = User::find($menteeId);
            if ($mentee) {
                Notification::send($mentee, new SessionCancelledNotification($this->backup['after']));
            }
        }

        return $result;
    }

    public function getDescription(): string
    {
        return "Create a new session " . ($this->getSessionId() ?? 'unknown');
    }
}
