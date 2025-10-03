<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router): void // 🌟🌟 تمرير Router كـ dependency 🌟🌟
    {

        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });


        $router->aliasMiddleware('admin', AdminMiddleware::class);
    }
}
