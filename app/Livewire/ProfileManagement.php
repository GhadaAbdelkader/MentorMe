<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Mentor;
use Illuminate\Support\Facades\Auth;

class ProfileManagement extends Component
{
    public $name;
    public $email;
    public $phone;

    public $specialization;
    public $bio;
    public $experience_years;
    public $available_hours;
    public $linkedin_url;
    public $is_available;

    public $isMentor = false;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ];

        if ($this->isMentor) {
            $rules = array_merge($rules, [
                'specialization' => 'required|string|max:255',
                'bio' => 'required|string|max:1000',
                'experience_years' => 'nullable|integer|min:0|max:80',
                'available_hours' => 'nullable|string|max:255',
                'linkedin_url' => 'nullable|url|max:255',
                'is_available' => 'boolean',
            ]);
        }

        return $rules;
    }


    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;

        if ($user->role === 'mentor') {
            $this->isMentor = true;
            $mentorProfile = $user->mentorProfile;

            if ($mentorProfile) {
                $this->specialization = $mentorProfile->specialization;
                $this->bio = $mentorProfile->bio;
                $this->experience_years = $mentorProfile->experience_years;
                $this->available_hours = $mentorProfile->available_hours;
                $this->linkedin_url = $mentorProfile->linkedin_url;
                $this->is_available = $mentorProfile->is_available;
            } else {
                $this->is_available = true;
            }
        }
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

        if ($this->isMentor) {
            Mentor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialization' => $this->specialization,
                    'bio' => $this->bio,
                    'experience_years' => $this->experience_years,
                    'available_hours' => $this->available_hours,
                    'linkedin_url' => $this->linkedin_url,
                    'is_available' => $this->is_available,
                ]
            );
        }

        session()->flash('success_message', 'تم تحديث ملفك الشخصي بنجاح!');
    }

    public function render()
    {
        return view('livewire.profile-management');
    }
}
