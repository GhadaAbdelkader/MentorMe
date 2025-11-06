<?php

namespace Modules\User\Livewire\Admin;

use Modules\User\Enums\UserStatus;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserStatusService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\User\Models\User;

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
        session(['previous_url' => url()->previous()]);

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
        return view('user::livewire.admin.user-profile-viewer');
    }
}
