<?php

namespace Modules\User\Livewire\Auth;

use Illuminate\Validation\ValidationException;
use Modules\User\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Component;


class Login extends Component
{
    public LoginForm $form;


    /**
     * @throws ValidationException
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('user::livewire.auth.login')->layout('layouts.guest');
    }
}
