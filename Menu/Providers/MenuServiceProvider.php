<?php
namespace App\Modules\Menu\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'Menu');

        //register assets
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/menu'),
        ], 'menu-assets');
    }
}
