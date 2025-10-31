<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;


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
