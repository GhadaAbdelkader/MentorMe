<?php

namespace Modules\User\Patterns\Base;

use Illuminate\Support\Facades\DB;
use Modules\User\Patterns\Services\SessionService;
use Modules\User\Patterns\Command\CommandActionInterface;
use Modules\User\Models\MentorshipSession;
use Exception;

abstract class AbstractActionCommand implements CommandActionInterface
{
    protected SessionService $service;
    protected array $data = [];
    protected array $backup = [];

    public function __construct(SessionService $service, array $data = [])
    {
        $this->service = $service;
        $this->data = $data;
    }

    abstract public function execute(): array;
    abstract public function undo(): array;
    abstract public function getDescription(): string;

    public function setBackup(array $backup): void
    {
        $this->backup = $backup;
    }

    public function getBackup(): array
    {
        return $this->backup;
    }

    protected function getSessionId(): ?int
    {
        return isset($this->data['id']) ? (int)$this->data['id'] : null;
    }

    /**
     * Helper: get session or throw
     */
    protected function findSessionOrFail(int $id): MentorshipSession
    {
        $session = MentorshipSession::find($id);
        if (!$session) {
            throw new Exception("The session ({$id}) does not exist");
        }
        return $session;
    }

    /**
     * Helper: wrap a callback in DB transaction and return standardized response
     */
    protected function wrapTransaction(callable $cb): array
    {
        return DB::transaction(function () use ($cb) {
            return $cb();
        });
    }
}
