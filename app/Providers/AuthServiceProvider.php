<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('isAdmin', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('isMentor', function (User $user) {
            return $user->role === 'mentor';
        });

        Gate::define('isMentee', function (User $user) {
            return $user->role === 'mentee';
        });

        Gate::before(function (User $user, string $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });
    }
}
