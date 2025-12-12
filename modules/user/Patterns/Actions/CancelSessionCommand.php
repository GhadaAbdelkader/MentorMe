<?php

namespace Modules\User\Patterns\Actions;

use Modules\User\Patterns\Base\AbstractActionCommand;
use Modules\User\Models\User;
use Modules\User\Patterns\Notifications\SessionCancelledNotification;
use Illuminate\Support\Facades\Notification;
use Modules\User\Patterns\Notifications\SessionScheduledNotification;

class CancelSessionCommand extends AbstractActionCommand
{
    public function execute(): array
    {
        $sessionId = $this->getSessionId();
        if (!$sessionId) {
            return ['success' => false, 'message' => 'Session ID Required'];
        }

        $sessionModel = \Modules\User\Models\MentorshipSession::findOrFail($sessionId);

        $result = $this->service->cancelSession($sessionModel);

        if ($result['success']) {
            $this->setBackup([
                'id' => $sessionId,
                'type' => 'cancel',
                'previous_state' => [
                    'status' => $result['before']['status'] ?? 'scheduled',
                    'cancelled_at' => $result['before']['cancelled_at'] ?? null
                ],
                'cancelled_at' => $result['after']['cancelled_at'] ?? now()->toDateTimeString()
            ]);

            $mentee = User::find($result['after']['mentee_id'] ?? null);
            if ($mentee) {
                Notification::send($mentee, new SessionCancelledNotification($result['after']));
            }
        }

        return $result;
    }

    public function undo(): array
    {
        $id = $this->backup['id'] ?? null;
        $previous = $this->backup['previous_state'] ?? [];

        if (!$id || empty($previous)) {
            return ['success' => false, 'message' => 'Insufficient data to backtrack'];
        }

        $data = [
            'status' => $previous['status'] ?? 'scheduled',
            'cancelled_at' => null
        ];

        $result = $this->service->updateSession($id, $data, true);

        if ($result['success']) {
            // Notify mentee about undo
            $mentee = User::find($result['after']['mentee_id'] ?? null);
            if ($mentee) {
                Notification::send($mentee, new SessionScheduledNotification($result['after']));
            }
            return ['success' => true, 'message' => 'The cancellation of the session was reversed.'];
        }

        return ['success' => false, 'message' => 'Failed to revert cancellation'];
    }

    public function getDescription(): string
    {
        return "Cancel a session " . ($this->getSessionId() ?? 'unknown');
    }
}
