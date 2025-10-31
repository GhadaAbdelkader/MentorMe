<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use App\Enums\UserStatus;
use Illuminate\Support\Facades\Auth;
use App\Contracts\UserRepositoryInterface;
class UserManagement extends Component
{
    /**
     *
     * @param int $userId
     * @param string $newStatus
     */

    public $users;
    public string $searchName = '';
    public string $filterRole = '';
    public string $filterExperience = '';
    #[Title('User Management')]
    public function mount()
    {
        $this->refreshUsers();
    }

    public function refreshUsers(): void
    {
        $repository = app(UserRepositoryInterface::class);
        $this->users = $repository->filterUsers(
            $this->searchName,
            $this->filterExperience,
            $this->filterRole
        );
    }

    public function updateStatus(int $userId, string $newStatus): void
    {
        $repository = app(\App\Repositories\UserRepository::class);
        $statusService = app(\App\Services\UserStatusService::class);

        $user = User::find($userId);

        if (!Auth::check() || Auth::user()->role !== 'admin') {
            $this->dispatch('toast', type: 'error', message: 'You must be logged in as an admin.');
            return;
        }

        try {
            $status = UserStatus::from($newStatus);
        } catch (\ValueError) {
            $this->dispatch('toast', type: 'error', message: 'Invalid status provided.');

            return;
        }

        $updated = $statusService->update($userId, $status);
        if ($updated) {
            $this->dispatch('toast', type: 'success', message: $status->successMessage($user->role));
            $this->users = $repository->filterUsers();
        } else {
            $this->dispatch('toast', type: 'error', message: 'Failed to update mentor status.');

        }
    }

    public function render()
    {
        return view('livewire.admin.user-management');
    }
}
