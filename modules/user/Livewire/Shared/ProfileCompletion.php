<?php

namespace Modules\User\Livewire\Shared;

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
        $this->user = Auth::user()->load(['mentorProfile', 'menteeProfile']);

        if ($this->user->mentorProfile) {
            $this->userType = 'mentor';
            $this->profile = $this->user->mentorProfile;
        } else {
            $this->userType = 'mentee';
            $this->profile = $this->user->menteeProfile;
        }

        $this->completionPercentage = $this->getCompletionPercentage();
    }

    public function refreshProgress()
    {
        $this->completionPercentage = $this->getCompletionPercentage();
    }

    public function getCompletionPercentage()
    {
        $baseFields = ['name', 'email', 'phone'];
        $filled = 0;
        $total = count($baseFields);

        foreach ($baseFields as $field) {
            if (!empty($this->user->{$field})) {
                $filled++;
            }
        }

        if ($this->profile && method_exists($this->profile, 'completionPercentage')) {
            $profilePercentage = $this->profile->completionPercentage();

            $profileFields = $this->profile::$profileFields ?? [];
            $profileFilled = round(($profilePercentage / 100) * count($profileFields));

            $filled += $profileFilled;
            $total += count($profileFields);
        }

        return round(($filled / $total) * 100);
    }

    public function getCompletionItems()
    {
        $items = [];

        $baseFields = [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
        ];

        foreach ($baseFields as $field => $label) {
            $items[] = [
                'field' => $field,
                'label' => $label,
                'filled' => !empty($this->user->{$field}),
            ];
        }

        if ($this->profile && method_exists($this->profile, 'completionItems')) {
            $items = array_merge($items, $this->profile->completionItems());
        }

        return $items;
    }

    public function render()
    {
        return view('user::livewire.shared.profile-completion', [
            'completionItems' => $this->getCompletionItems(),
        ]);
    }
}
