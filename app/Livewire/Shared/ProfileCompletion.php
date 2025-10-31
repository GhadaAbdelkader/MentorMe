<?php

namespace App\Livewire\Shared;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProfileCompletion extends Component
{
    public $user;
    public $userType;
    public $profile;
    public $completionPercentage = 0;

    protected $listeners = ['profileUpdated' => 'refreshProgress'];

    public function mount()
    {
        $this->user = Auth::user();

        if ($this->user->mentorProfile) {
            $this->userType = 'mentor';
            $this->profile = $this->user->mentorProfile;
        } else {
            $this->userType = 'mentee';
            $this->profile = $this->user;
        }

        $this->completionPercentage = $this->getCompletionPercentage();
    }

    public function refreshProgress()
    {
        $this->completionPercentage = $this->getCompletionPercentage();
    }

    public function getCompletionPercentage()
    {

        if ($this->userType === 'mentor' && method_exists($this->profile, 'completionPercentage')) {
            return $this->profile->completionPercentage();
        }

        $filled = 0;
        $total = 3;

        if (!empty($this->profile->name)) $filled++;
        if (!empty($this->profile->email)) $filled++;
        if (!empty($this->profile->phone)) $filled++;

        return round(($filled / $total) * 100);
    }

    public function render()
    {
        return view('livewire.shared.profile-completion');
    }
}
