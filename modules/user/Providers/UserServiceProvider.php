<?php
namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\User\Livewire\Dashboard;

class UserServiceProvider extends ServiceProvider
{

    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'user');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        Livewire::component('user.login', \Modules\User\Livewire\Auth\Login::class);
        Livewire::component('user.register', \Modules\User\Livewire\Auth\Register::class);
        Livewire::component('user.dashboard', Dashboard::class);

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(LivewireServiceProvider::class);


    }
}
