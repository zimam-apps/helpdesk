<?php

namespace Workdo\CustomerLogin\Providers;

use Illuminate\Support\ServiceProvider;
use Workdo\CustomerLogin\Providers\EventServiceProvider;
use Workdo\CustomerLogin\Providers\RouteServiceProvider;

class CustomerLoginServiceProvider extends ServiceProvider
{

    protected $moduleName = 'CustomerLogin';
    protected $moduleNameLower = 'customerlogin';

    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'customer-login');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerTranslations();
        $this->registerMiddleware('CustomerLogin');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    protected function registerMiddleware($alias)
    {
        $router = $this->app['router'];

        $router->aliasMiddleware($alias, \Workdo\CustomerLogin\Http\Middleware\CustomerLogin::class);
    }
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(__DIR__.'/../Resources/lang');
        }
    }
}