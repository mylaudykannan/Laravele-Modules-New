<?php
namespace App\Modules\Page\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class PageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'Page');

        $this->loadModuleConfig();


        if (isset($_GET['lang'])) {
            $locale = $_GET['lang'];
        } else {
            $locale = 'en';
        }
        view()->share('locale', $locale);
        App::setLocale($locale);
    }

    protected function loadModuleConfig()
    {
        $configPath = __DIR__ . '/../config/page.php';

        $this->mergeConfigFrom($configPath, 'page');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $configPath => config_path('page.php'),
            ], 'page');
        }
    }
}
