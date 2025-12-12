<?php

namespace Modules\User\Patterns\Actions;

use Modules\User\Patterns\Base\AbstractActionCommand;
use Modules\User\Models\User;
use Modules\User\Patterns\Notifications\SessionCancelledNotification;
use Illuminate\Support\Facades\Notification;
use Modules\User\Patterns\Notifications\SessionScheduledNotification;

class DeleteSessionCommand extends AbstractActionCommand
{
    public function execute(): array
    {
        $sessionId = $this->getSessionId();
        if (!$sessionId) {
            return ['success' => false, 'message' => 'Session ID Required'];
        }

        $session = $this->service->findWithRelations($sessionId);
        if (!$session) {
            return ['success' => false, 'message' => 'The session does not exist'];
        }

        $fullBackup = [
            'session' => $session->toArray(),
            'mentee' => $session->mentee ? $session->mentee->only(['id','name','email']) : null,
            'mentor' => $session->mentor ? $session->mentor->only(['id','name','email']) : null,
            'deleted_at' => now()->toDateTimeString()
        ];

        $this->setBackup([
            'id' => $sessionId,
            'type' => 'delete',
            'full_data' => $fullBackup,
            'deleted_at' => now()->toDateTimeString()
        ]);

        // Send notification to mentee
        if ($fullBackup['mentee']) {
            $mentee = User::find($fullBackup['mentee']['id']);
            if ($mentee) {
                Notification::send($mentee, new SessionCancelledNotification($fullBackup['session']));
            }
        }

        return $this->service->forceDeleteSession($sessionId);
    }

    public function undo(): array
    {
        $sessionData = $this->backup['full_data']['session'] ?? [];
        if (empty($sessionData)) {
            return ['success' => false, 'message' => 'Insufficient data to restore the session.'];
        }

        $restoreResult = $this->service->restoreSessionFromData($sessionData);

        if ($restoreResult['success']) {
            // Notify mentee about restoration
            $mentee = User::find($sessionData['mentee_id'] ?? null);
            if ($mentee) {
                Notification::send($mentee, new SessionScheduledNotification($sessionData));
            }

            return [
                'success' => true,
                'message' => 'The deleted session was successfully restored.',
                'session_id' => $restoreResult['session_id']
            ];
        }

        return ['success' => false, 'message' => $restoreResult['message'] ?? 'Restore failed'];
    }

    public function getDescription(): string
    {
        return "Delete session " . ($this->getSessionId() ?? 'unknown');
    }
}
