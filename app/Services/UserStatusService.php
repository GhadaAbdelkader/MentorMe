<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserStatusService
{
    public function update(int $userId, UserStatus $status): bool
    {
        try {
            $user = User::find($userId);

            if (!$user) {
                Log::warning("User not found: {$userId}");
                return false;
            }

            $user->status = $status->value;
            $user->save();

            return true;

        } catch (\Throwable $e) {
            Log::error("Error updating user status: {$e->getMessage()} for user {$userId}");
            Log::info("Admin #".Auth::id()." changed status of user #{$userId} to {$status->value}");
            return false;
        }

    }
}
