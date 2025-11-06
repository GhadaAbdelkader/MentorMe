<?php

namespace Modules\User\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Actions\Logout;

class DeleteUserForm extends Component
{

    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }

    public function render()
        {
            return view('user::livewire.profile.delete-user-form');
    }
};
