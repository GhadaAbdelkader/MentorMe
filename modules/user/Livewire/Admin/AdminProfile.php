<?php

namespace Modules\User\Livewire\Admin;


use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AdminProfile extends Component
{
    public $name;
    public $email;
    public $phone;



    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ];
    }


    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;


    }


    public function saveProfile(): void
    {
        $this->validate();

        $user = Auth::user();

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);



        session()->flash('success_message', 'Your profile has been updated successfully');
    }

    public function render()
    {
        return view('user::livewire.admin.admin-profile');
    }
}
