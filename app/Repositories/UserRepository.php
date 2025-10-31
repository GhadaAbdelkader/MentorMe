<?php

namespace App\Repositories;

use App\Models\Mentor;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Contracts\UserRepositoryInterface;

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


    public function filterUsers(?string $name = null, ?string $experience_years = null, ?string $role =null): \Illuminate\Database\Eloquent\Collection
{
    $query = User::query();
    $query->whereIn('role', ['mentor', 'mentee']);
    if ($name) {
        $query->where('name', 'like', "%{$name}%");
    }
    if ($role) {
        $query->where('role', $role);
    }
    if ($role === 'mentor' && $experience_years) {
        $query->whereHas('mentorProfile', function (Builder $query) use ($experience_years) {
            if ($experience_years === 'junior') {
                $query->where('experience_years', '<=', 2);
            } elseif ($experience_years === 'mid') {
                $query->whereBetween('experience_years', [3, 5]);
            } elseif ($experience_years === 'senior') {
                $query->where('experience_years', '>', 5);
            }

        });
    }

    return $query->orderByDesc('created_at')->get();
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
