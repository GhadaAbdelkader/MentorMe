<?php

namespace Modules\User\Livewire\Admin;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\User\Contracts\UserRepositoryInterface;
use Modules\User\Enums\UserStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepository;

class UserManagement extends Component
{
    /**
     *
     * @param int $userId
     * @param string $newStatus
     */
    public string $searchName = '';
    public string $filterRole = '';
    public string $filterExperience = '';



    public function getUsersProperty(): LengthAwarePaginator
    {
        $repository = app(UserRepositoryInterface::class);
        return $repository->filterUsers(
            $this->searchName,
            $this->filterExperience,
            $this->filterRole
        );
    }


    public function updateStatus(int $userId, string $newStatus): void
    {
        $repository = app(\Modules\User\Repositories\UserRepository::class);
        $statusService = app(\Modules\User\Services\UserStatusService::class);

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
        return view('user::livewire.admin.user-management', [
            'users' => $this->users,
        ]);
    }
}
