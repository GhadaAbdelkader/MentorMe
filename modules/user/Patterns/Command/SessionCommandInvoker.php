<?php

namespace Modules\User\Patterns\Command;

use Modules\User\Models\CommandHistory;
use Modules\User\Patterns\Services\SessionService;
use Modules\User\Patterns\Validation\SessionValidation;
use Illuminate\Support\Facades\Log;

class SessionCommandInvoker
{
    private int $userId;
    private string $userType;
    private SessionService $service;

    public function __construct(int $userId, string $userType = 'user', ?SessionService $service = null)
    {
        $this->userId = $userId;
        $this->userType = $userType;
        $this->service = $service ?? new SessionService();
    }

    public function execute(CommandActionInterface $command): array
    {
        $result = $command->execute();

        if (!empty($result['success']) && $result['success']) {
            CommandHistory::create([
                'command_type' => get_class($command),
                'user_id' => $this->userId,
                'user_type'    => $this->userType ?? 'user',
                'payload' => $command->getBackup(),
                'executed_at' => now(),
            ]);
        }

        return $result;
    }

    public function undo(): array
    {
        $last = CommandHistory::notUndone()->where('user_id', $this->userId)->latest()->first();

        if (!$last) {
            Log::warning('No command to undo for user:', ['user_id' => $this->userId]);
            return ['success' => false, 'message' => 'There are no undo processes.'];
        }

        // Check undo timeframe (24 hours) using executed_at
        try {
            SessionValidation::validateUndoTimeframe($last->executed_at);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        Log::info('Undo - Found command:', [
            'command_type' => $last->command_type,
            'history_id' => $last->id,
            'payload_keys' => array_keys($last->payload ?? [])
        ]);

        $backup = $last->payload;

        if (is_string($backup)) {
            $backup = json_decode($backup, true);
            Log::info('Undo - Decoded backup from JSON');
        }

        // instantiate the command using the service and backup
        $commandClass = $last->command_type;
        if (!class_exists($commandClass)) {
            Log::error('Undo - Command class not found', ['class' => $commandClass]);
            return ['success' => false, 'message' => 'Unable to restore command (class not found).'];
        }

        $command = new $commandClass($this->service, $backup);

        if (method_exists($command, 'setBackup')) {
            $command->setBackup($backup);
        }

        $result = $command->undo();

        if (!empty($result['success']) && $result['success']) {
            $last->update(['undone_at' => now()]);
            Log::info('Undo - History marked as undone');
        }

        return $result;
    }
}
