<?php

namespace App\Contracts;

interface  UserRepositoryInterface
{
    public function updateStatus(int $userId, string $newStatus):bool;

    public function filterUsers(?string $name = null, ?string $experience_years = null, ?string $role = null);
}
