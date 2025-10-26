<?php
namespace App\Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Gate;

class UserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'User');
        Schema::defaultStringLength(191);

        //for command register
        $this->commands([
            \App\Modules\User\Console\Commands\UserCommands::class
        ]);

        //defining super admin
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });
    }
}
