<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PasswordConfirmationService
{

    /**
     * @throws ValidationException
     */
    public function confirm(string $password): void
    {
        $user = Auth::user();



        if (! $user) {
            throw ValidationException::withMessages([
                'password' => __('auth.unauthenticated'),
            ]);
        }

        $isValid = Auth::guard('web')->validate([
            'email' => $user->email,
            'password' => $password,
        ]);

        if (! $isValid) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);
    }



}
