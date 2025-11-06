<?php

namespace Modules\User\Http\Controllers\Auth;

use Modules\User\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }

        if (! $user->hasVerifiedEmail()) {

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
                Log::info("User {$user->id} verified their email successfully.");
            }

            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
