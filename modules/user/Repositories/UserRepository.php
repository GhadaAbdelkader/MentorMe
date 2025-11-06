<?php

namespace Modules\User\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\User\Contracts\UserRepositoryInterface;
use Modules\User\Models\Mentor;
use Modules\User\Models\User;

class UserRepository implements UserRepositoryInterface
{

    public function updateStatus(int $userId, string $newStatus): bool
    {
        $user = User::find($userId);

        if (!$user || $user->role !== 'mentor') {
            return false;
        }

        $user->status = $newStatus;
        return $user->save();
    }

    public function filterUsers(
        ?string $name = null,
        ?string $experience_years = null,
        ?string $role = null
    ): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = User::query()->whereIn('role', ['mentor', 'mentee']);

        $this->applyNameFilter($query, $name);
        $this->applyRoleFilter($query, $role);
        $this->applyExperienceFilter($query, $role, $experience_years);

        return $query->orderByDesc('created_at')->paginate(10);
    }

    private function applyNameFilter(Builder $query, ?string $name): void
    {
        if ($name) {
            $query->where('name', 'like', "%{$name}%");
        }
    }

    private function applyRoleFilter(Builder $query, ?string $role): void
    {
        if ($role) {
            $query->where('role', $role);
        }
    }

    private function applyExperienceFilter(Builder $query, ?string $role, ?string $experience_years): void
    {
        if ($role === 'mentor' && $experience_years) {
            $query->whereHas('mentorProfile', function (Builder $q) use ($experience_years) {
                if ($experience_years === 'junior') {
                    $q->where('experience_years', '<=', 2);
                } elseif ($experience_years === 'mid') {
                    $q->whereBetween('experience_years', [3, 5]);
                } elseif ($experience_years === 'senior') {
                    $q->where('experience_years', '>', 5);
                }
            });
        }
    }

    public function findWithRelations(int $id)
    {
        return User::with('mentorProfile')->findOrFail($id);
    }

    public function findMentorWithCompletion(int $userId): Mentor
    {
        return Mentor::where('user_id', $userId)
            ->with('user')
            ->firstOrFail();
    }
}
