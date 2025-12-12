<?php

namespace Modules\User\Patterns\Actions;

use Modules\User\Patterns\Base\AbstractActionCommand;
use Modules\User\Models\User;
use Modules\User\Patterns\Notifications\SessionRescheduledNotification;
use Illuminate\Support\Facades\Notification;

class ModifySessionCommand extends AbstractActionCommand
{
    public function execute(): array
    {
        $result = $this->service->updateSession($this->data['id'], $this->data);

        if ($result['success']) {
            $this->setBackup([
                'id' => $this->data['id'],
                'before' => $result['before'],
                'after' => $result['after'],
                'type' => 'modify'
            ]);

            // Notify mentee about changes
            $mentee = User::find($result['after']['mentee_id'] ?? null);
            if ($mentee) {
                Notification::send($mentee, new SessionRescheduledNotification($result['after']));
            }
        }

        return $result;
    }

    public function undo(): array
    {
        return $this->service->updateSession($this->backup['id'], $this->backup['before'], true);
    }

    public function getDescription(): string
    {
        return "Modify session " . ($this->getSessionId() ?? 'unknown');
    }
}
