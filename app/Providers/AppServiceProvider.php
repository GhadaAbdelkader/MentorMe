<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Gate;
use Modules\User\Contracts\UserRepositoryInterface;
use Modules\User\Repositories\UserRepository;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router): void
    {

        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });


        $router->aliasMiddleware('admin', AdminMiddleware::class);
    }
}
