<?php

namespace Modules\User\Enums;

enum UserStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Pending = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Pending => 'Pending',
        };
    }

    public function successMessage(string $role): string
    {
        $roleLabel = $role === 'mentor' ? 'mentor' : 'mentee';
        return match ($this) {
            self::Active => "The {$roleLabel} account has been activated successfully.",
            self::Inactive => "The {$roleLabel} account has been deactivated successfully.",
            self::Pending => "The {$roleLabel} account status has been reset to pending.",
        };
    }
}
