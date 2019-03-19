<?php

namespace Lebenlabs\SimpleCMS;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use Lebenlabs\SimpleCMS\Contracts\CanEditMenuItem;
use Lebenlabs\SimpleCMS\Contracts\CanManagePublicaciones;
use Lebenlabs\SimpleCMS\Http\Middleware\CanEditMenu;
use Lebenlabs\SimpleCMS\Http\Middleware\CanViewPublicacion;
use Lebenlabs\SimpleCMS\Http\Middleware\MenuMenuItemExisteYPertenece;
use Lebenlabs\SimpleCMS\Http\Middleware\PublicacionExiste;
use SimpleStorage\Services\SimpleStorageService;

class SimpleCMSServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'Lebenlabs');

        // Load Views
        $this->loadViewsFrom(__DIR__.'/Resources/Views', 'Lebenlabs/SimpleCMS');

        // Publish views
        $this->publishes([__DIR__.'/Resources/Views' => resource_path('views/vendor/Lebenlabs/SimpleCMS')]);

        // Load Routes
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        // Publish Migrations
        $this->publishes([__DIR__.'/Database/Migrations' => database_path('migrations')]);

        // Register middleware
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('CanEditMenu', CanEditMenu::class);
        $router->pushMiddlewareToGroup('CanEditMenuItem', CanEditMenuItem::class);
        $router->pushMiddlewareToGroup('MenuMenuItemExisteYPertenece', MenuMenuItemExisteYPertenece::class);
        $router->pushMiddlewareToGroup('PublicacionExiste', PublicacionExiste::class);
        $router->pushMiddlewareToGroup('CanViewPublicacion', CanViewPublicacion::class);
        $router->pushMiddlewareToGroup('CanManagePublicaciones', CanManagePublicaciones::class);

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Merge config of simple cms package
        $this->mergeConfigFrom(__DIR__.'/../config/simplecms.php', 'simplecms');

        // Register the service the package provides.
        $this->app->bind(SimpleCMS::class, function() {
            return new SimpleCMS(
                app('em'),
                app(Repository::class),
                app(ImageManager::class),
                app(SimpleStorageService::class)
            );
        });

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['SimpleCMS'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([__DIR__.'/../config/simplecms.php' => config_path('simplecms.php')]);
        $this->publishes([__DIR__.'/../config/image.php' => config_path('image.php')]);

        //Publish views
        $this->publishes([__DIR__.'/Resources/Views' => resource_path('views/vendor/Lebenlabs/SimpleCMS')]);

        // Publish Migrations
        $this->publishes([__DIR__.'/Database/Migrations' => database_path('migrations')]);

    }
}
