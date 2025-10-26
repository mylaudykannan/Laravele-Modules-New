<?php
namespace App\Modules\News\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class NewsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadViewsFrom(__DIR__ . '/../views', 'News');

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
        $configPath = __DIR__ . '/../config/news.php';

        $this->mergeConfigFrom($configPath, 'news');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $configPath => config_path('news.php'),
            ], 'news');
        }
    }
}
