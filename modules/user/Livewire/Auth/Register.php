<?php

namespace Modules\User\Livewire\Auth;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Livewire\Component;
use Modules\User\Models\User;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'mentee';

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', Rule::in(['mentor', 'mentee'])],
        ];
    }

    public function register(): void
    {
        $validated = $this->validate();

        $validated['role'] = $this->role;

        $service = app(\App\Services\UserRegistrationService::class);

        $user = $service->register($validated);

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $this->redirect(route('verification.notice', absolute: false), navigate: true);
            return;
        }

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('user::livewire.auth.register')->layout('layouts.guest');
    }
}
