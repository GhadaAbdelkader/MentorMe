<?php

namespace Modules\User\Livewire\Mentee;

use AllowDynamicProperties;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\Mentee;

#[AllowDynamicProperties] class MenteeProfile extends Component
{
    public $name;
    public $email;
    public $phone;
    public $interests;
    public $goals;
    public $current_level;


    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'interests' => 'string|max:255',
            'goals' => 'required|string|max:1000',
            'current_level' => 'required|string|max:255',
        ];
    }

    public function mount()
    {
        $user = Auth::user();
        $menteeProfile = $user->menteeProfile ?? new Mentee();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->interests = $menteeProfile->interests;
        $this->goals = $menteeProfile->goals;
        $this->current_level = $menteeProfile->current_level;

    }

    public function saveProfile()
    {
        $this->validate();

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);


        $menteeProfile = Mentee::updateOrCreate(
            ['user_id' => $user->id],
            [
                'interests' => $this->interests,
                'goals' => $this->goals,
                'current_level' => $this->current_level,

            ]
        );
        $this->mentee =  $menteeProfile->fresh();
        session()->flash('success_message', 'Your profile has been updated successfully');
        $this->dispatch('profileUpdated');
        $this->dispatch('$refresh');
    }

    public function render()
    {
        return view('user::livewire.mentee.mentee-profile');
    }
}
