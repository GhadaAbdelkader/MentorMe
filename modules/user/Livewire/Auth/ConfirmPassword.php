<?php

namespace Modules\User\Livewire\Auth;

use App\Services\PasswordConfirmationService;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ConfirmPassword extends Component
{
    public string $password = '';


    public function confirmPassword(): void
    {
        $service = app(PasswordConfirmationService::class);

        try {
            $service->confirm($this->password);
            $this->redirectIntended(route('dashboard'));
        } catch (ValidationException $e) {
            $this->addError('password', __('auth.password'));
        }

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('user::livewire.auth.confirm-password')->layout('layouts.guest');
    }
}
