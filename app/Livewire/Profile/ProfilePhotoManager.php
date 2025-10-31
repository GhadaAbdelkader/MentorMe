<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoManager extends Component
{
    use WithFileUploads;

    public $photo;

    public function savePhoto()
    {
        $this->validate([
            'photo' => 'nullable|image|max:1024',
        ]);

        if (!$this->photo) {
            session()->flash('photo_error', __('Please choose image'));
            return;
        }

        $user = Auth::user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $this->photo->store('profile-photos', 'public');

        $user->profile_photo_path = $path;
        $user->save();

        $this->reset('photo');
        session()->flash('photo_success', __('Image was saved successfully'));
    }


    public function deletePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }

        session()->flash('photo_deleted', __('Image was deleted successfully'));
    }

    public function render()
    {
        return view('livewire.profile.profile-photo-manager');
    }
}
