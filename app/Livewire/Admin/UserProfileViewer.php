<?php

namespace App\Livewire\Admin;

use App\Enums\UserStatus;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserStatusService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserProfileViewer extends Component
{


    public $userId;
    public User $user;
    public bool $isAdminViewing = false;

    protected UserRepository $userRepo;
    protected UserStatusService $statusService;

    public function boot(UserRepository $userRepo, UserStatusService $statusService)
    {
        $this->userRepo = $userRepo;
        $this->statusService = $statusService;
    }
    public function mount($userId)
    {

        $this->userId = $userId;
        $this->user = $this->userRepo->findWithRelations($userId);

        $this->isAdminViewing = Auth::check() && Auth::user()->role === 'admin';
    }



    public function updateStatus(string $newStatus)
    {
        if ($this->statusService->update($this->userId, UserStatus::from($newStatus))){
            session()->flash('message', "Status has been updated  {$newStatus} successfully.");
            $this->user =$this->userRepo->findWithRelations($this->userId);
            return;
        }
        session()->flash('error_message', "Failed to update the status.");
    }
    public function isMentorProfileFilled(): bool
    {
        $mentor = $this->user->mentorProfile ?? null;

        if (!$mentor) {
            return false;
        }

        return !empty($mentor->bio)
            && !empty($mentor->specialization)
            && !empty($mentor->experience_years);
    }
    public function render()
    {
        return view('livewire.admin.user-profile-viewer');
    }
}
