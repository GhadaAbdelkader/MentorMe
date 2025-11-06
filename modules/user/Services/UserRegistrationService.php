<?php

namespace Modules\User\Services;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;


class UserRegistrationService
{
    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = false;

        $user = User::create($data);

        Auth::login($user);

        dispatch(function () use ($user) {
            event(new Registered($user));
        })->afterResponse();

        return $user;
    }
}
