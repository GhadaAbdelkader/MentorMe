<?php

namespace Modules\User\Patterns\Command;

interface CommandActionInterface
{
    public function execute(): array;
    public function undo(): array;
    public function getDescription(): string;
    public function getBackup(): array;
    public function setBackup(array $backup): void;
}
