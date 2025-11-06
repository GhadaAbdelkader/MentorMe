<?php

namespace Modules\User\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke(): void
    {
        $userId = Auth::id();

        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();

        Log::info(" User {$userId} logged out successfully.");
    }
}
