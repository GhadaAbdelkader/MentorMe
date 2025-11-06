<?php

namespace Modules\User\Livewire\layout;

use Modules\User\Livewire\Actions\Logout;
use Livewire\Component;
class Navigation extends Component
{
    public function logout(Logout $logout): \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    {
        $logout();
        return redirect('/');
    }

    public function render()
    {
        return view('user::livewire.layout.navigation');
    }

}
