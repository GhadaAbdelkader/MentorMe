<?php

namespace App\Services;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;





class EmailVerificationService
{
    /**
     * Send the verification email to the authenticated user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if (! $user instanceof MustVerifyEmail) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->sendEmailVerificationNotification();
    }

    /**
     * Generate a temporary signed verification link (for local environments only).
     */
    public function generateTestLink(): ?string
    {
        $user = Auth::user();

        if (!App::environment('local')) {
            return null;
        }

        if (!$user instanceof MustVerifyEmail || $user->hasVerifiedEmail()) {
            return null;
        }

        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }
}
