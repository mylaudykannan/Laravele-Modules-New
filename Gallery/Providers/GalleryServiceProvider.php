<?php
namespace App\Modules\Gallery\Providers;

use Illuminate\Support\ServiceProvider;

class GalleryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'Gallery');

        //register assets
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/gallery'),
        ], 'gallery-assets');
    }
}
