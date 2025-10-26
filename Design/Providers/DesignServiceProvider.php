<?php
namespace App\Modules\Design\Providers;

use Illuminate\Support\ServiceProvider;

class DesignServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'Design');

    }
}
