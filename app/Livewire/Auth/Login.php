<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

// سنقوم بتضمين LoginForm الخاص بـ Breeze/Fortify
// هذا المكون يعتمد على Livewire Form Object وهو أفضل لـ Login
class Login extends Component
{
    public LoginForm $form;


    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
